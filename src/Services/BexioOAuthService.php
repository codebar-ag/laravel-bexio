<?php

namespace CodebarAg\Bexio\Services;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Exceptions\UserinfoVerificationException;
use CodebarAg\Bexio\Requests\OpenID\FetchUserinfoRequest;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class BexioOAuthService
{
    /**
     * Get a valid authenticator, refreshing it if expired
     *
     * @param  BexioOAuthTokenStore  $tokenStore  Token store to get/store authenticator
     * @param  BexioConnector  $connector  Connector to use for refresh
     * @param  string|null  $identifier  Optional identifier for multi-tenant scenarios
     * @return AccessTokenAuthenticator|null The valid authenticator or null if none found
     */
    public function getValidAuthenticator(BexioOAuthTokenStore $tokenStore, BexioConnector $connector, ?string $identifier = null): ?AccessTokenAuthenticator
    {
        $authenticator = $tokenStore->get($identifier);

        if (! $authenticator || ! ($authenticator instanceof AccessTokenAuthenticator)) {
            return null;
        }

        if ($authenticator->hasExpired()) {
            try {
                $authenticator = $connector->refreshAccessToken($authenticator);
                if (! $authenticator) {
                    throw new \RuntimeException('Refresh token request returned null');
                }
                $tokenStore->put($authenticator, $identifier);
            } catch (\Throwable $e) {
                throw new \RuntimeException('Failed to refresh authenticator: '.$e->getMessage(), 0, $e);
            }
        }

        return $authenticator;
    }

    /**
     * Fetch userinfo from Bexio using the authenticator.
     *
     * @throws \Throwable
     */
    public function fetchUserinfo(AccessTokenAuthenticator $authenticator, BexioConnector $connector): array
    {
        $connector->authenticate($authenticator);
        $request = new FetchUserinfoRequest;
        $response = $connector->send($request);
        $userinfo = $response->json();

        return $userinfo;
    }

    /**
     * Verify the userinfo response from Bexio.
     *
     * @param  array  $userinfo  The userinfo response from Bexio
     * @param  array|null  $allowedEmails  List of allowed email addresses
     *
     * @throws UserinfoVerificationException
     */
    public function verifyUserinfo(array $userinfo, ?array $allowedEmails = null): void
    {
        if (! ($userinfo['email_verified'] ?? false)) {
            throw new UserinfoVerificationException(
                'Bexio account email must be verified.'
            );
        }

        $email = $userinfo['email'] ?? null;
        if (! $email) {
            throw new UserinfoVerificationException(
                'No email address provided by Bexio account.'
            );
        }

        if (empty($allowedEmails)) {
            throw new UserinfoVerificationException(
                'No allowed emails configured.'
            );
        }

        if (! in_array($email, $allowedEmails)) {
            throw new UserinfoVerificationException(
                sprintf(
                    'Email address %s is not authorized to connect this Bexio account.',
                    $email
                )
            );
        }
    }
}
