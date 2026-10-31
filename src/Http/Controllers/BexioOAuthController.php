<?php

namespace CodebarAg\Bexio\Http\Controllers;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationValidateResolver;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Saloon\Exceptions\InvalidStateException;

class BexioOAuthController extends Controller
{
    public function __construct(
        protected BexioOAuthConfigResolver $resolver
    ) {}

    protected function connector(): BexioConnector
    {
        $configuration = $this->resolver->resolve();

        return new BexioConnector($configuration);
    }

    public function redirect(): RedirectResponse
    {
        $configuration = $this->resolver->resolve();

        // Use the same connector instance for building the URL and reading the state,
        // otherwise the stored state would not match the one sent to bexio.
        $connector = $this->connector();

        $redirectUrl = $connector->getAuthorizationUrl(scopes: $configuration->scopes);

        Session::put('bexio_oauth_state', $connector->getState());

        return Redirect::away($redirectUrl);
    }

    /**
     * @throws BindingResolutionException
     * @throws InvalidStateException
     */
    public function callback(Request $request): RedirectResponse
    {
        // Handle OAuth errors (like user rejection)
        if ($request->has('error')) {
            return Redirect::to(config('bexio.redirect_url', '/'))
                ->with('bexio_oauth_success', false)
                ->with('bexio_oauth_message', 'OAuth authorization failed: '.$request->query('error'));
        }

        if ($request->missing('code') || $request->missing('state')) {
            return Redirect::to(config('bexio.redirect_url', '/'))
                ->with('bexio_oauth_success', false)
                ->with('bexio_oauth_message', 'Missing required parameters: code or state.');
        }

        // The state is single use. Without a stored state the callback cannot be
        // verified, so it is rejected instead of skipping the state check.
        $expectedState = Session::pull('bexio_oauth_state');

        if (! is_string($expectedState) || $expectedState === '') {
            return Redirect::to(config('bexio.redirect_url', '/'))
                ->with('bexio_oauth_success', false)
                ->with('bexio_oauth_message', 'Invalid or expired OAuth state.');
        }

        $authenticator = $this->connector()->getAccessToken(
            code: $request->query('code'),
            state: $request->query('state'),
            expectedState: $expectedState
        );

        $configuration = $this->resolver->resolve();
        $connector = new BexioConnector($configuration, autoResolveAndAuthenticate: false);
        $connector->authenticate($authenticator);

        $validationResult = App::make(BexioOAuthAuthenticationValidateResolver::class)
            ->resolve(connector: $connector);

        if (! $validationResult->isValid) {
            // If the resolver provided a custom redirect, use it
            if ($validationResult->redirect) {
                return $validationResult->redirect;
            }

            // Otherwise, use the default redirect
            return Redirect::to(config('bexio.redirect_url', '/'))
                ->with('bexio_oauth_success', false)
                ->with('bexio_oauth_message', 'Authentication validation failed.');
        }

        App::make(BexioOAuthAuthenticationStoreResolver::class)
            ->put(authenticator: $authenticator); // @phpstan-ignore-line

        return Redirect::to(config('bexio.redirect_url', '/'))
            ->with('bexio_oauth_success', true)
            ->with('bexio_oauth_message', 'Successfully authenticated with Bexio.');
    }
}
