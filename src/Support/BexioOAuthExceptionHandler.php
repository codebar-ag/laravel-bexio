<?php

namespace CodebarAg\Bexio\Support;

use CodebarAg\Bexio\Support\BexioOAuthViewBuilder;

class BexioOAuthExceptionHandler
{
    public function __construct(private BexioOAuthViewBuilder $bexioOAuthViewBuilder) {}


    /**
     * Render a user-friendly OAuth error response based on the exception/context.
     *
     * @param \Throwable $e
     * @param string|null $context
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function render(\Throwable $e, ?string $context = null)
    {
        logger()->error("[BexioOAuth] Exception in $context", ['exception' => $e]);

        if ($e instanceof \Saloon\Exceptions\InvalidStateException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid OAuth Callback',
                'The authorization state did not match. Please try connecting again.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                400
            );
        }

        if ($e instanceof \Saloon\Exceptions\Request\Statuses\UnauthorizedException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Bexio Authentication Error',
                'Authorization failed. Please ensure your Bexio connection is set up.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                401
            );
        }

        if ($e instanceof \Saloon\Exceptions\Request\Statuses\ForbiddenException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Verification Failed',
                'Verification failed during callback.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                403
            );
        }

        if ($e instanceof \Saloon\Exceptions\Request\RequestException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Bexio API Error',
                'A request to Bexio failed. Please try again later.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                500
            );
        }

        if ($e instanceof \Saloon\Exceptions\OAuthConfigValidationException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'OAuth Configuration Error',
                'There was a problem with the OAuth configuration. Please contact support.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                500
            );
        }

        if ($e instanceof \CodebarAg\Bexio\Exceptions\UserinfoVerificationException) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Verification Failed',
                'Your account could not be verified. Please contact support or try a different account.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                403
            );
        }

        if ($context === 'callback') {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid OAuth Callback',
                'Token exchange failed. Please try connecting again.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                400
            );
        }

        return $this->bexioOAuthViewBuilder->build(
            'danger',
            'OAuth Error',
            'An unexpected error occurred. Please try again.',
            ['url' => url('/'), 'label' => 'Back to Home'],
            null,
            500
        );
    }
}
