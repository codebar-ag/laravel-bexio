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
     * Exchange authorization code for an authenticator using BexioConnector.
     *
     * @return mixed Authenticator (type depends on BexioConnector::getAccessToken)
     *
     * @throws \Throwable
     */
    public function exchangeCodeForAuthenticator(string $code, string $state, string $expectedState)
    {
        $connector = new BexioConnector;

        return $connector->getAccessToken($code, $state, $expectedState);
    }

    /**
     * Refresh and persist the authenticator, regardless of expiry.
     *
     * @return AccessTokenAuthenticator|null
     */
    public function refreshAuthenticator(BexioOAuthTokenStore $tokenStore, BexioConnector $connector)
    {
        $authenticator = $tokenStore->get();
        if ($authenticator) {
            $authenticator = $connector->refreshAccessToken($authenticator);
            $tokenStore->put($authenticator);
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
     * Verify userinfo claims (email, email_verified).
     *
     * @throws \CodebarAg\Bexio\Exceptions\UserinfoVerificationException
     */
    public function verifyUserinfo(array $userinfo): void
    {
        $expectedEmail = config('bexio.auth.oauth_email');
        if (! ($userinfo['email_verified'] ?? false) || ($userinfo['email'] ?? null) !== $expectedEmail) {
            throw new UserinfoVerificationException(
                sprintf(
                    'Account verification failed: used email was %s, expected email was %s, email_verified: %s',
                    $userinfo['email'] ?? 'null',
                    $expectedEmail ?? 'null',
                    isset($userinfo['email_verified']) ? var_export($userinfo['email_verified'], true) : 'null'
                )
            );
        }
    }
}
