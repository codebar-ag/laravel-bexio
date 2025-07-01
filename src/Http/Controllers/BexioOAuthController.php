<?php

namespace CodebarAg\Bexio\Http\Controllers;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticatonStoreResolver;
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

        $redirectUrl = $this->connector()->getAuthorizationUrl(scopes: $configuration->scopes);

        Session::put('bexio_oauth_state', $this->connector()->getState());

        return Redirect::away($redirectUrl);
    }

    /**
     * @throws BindingResolutionException
     * @throws InvalidStateException
     */
    public function callback(Request $request): RedirectResponse
    {
        $authenticator = $this->connector()->getAccessToken(
            code: $request->get('code'),
            state: $request->get('state'),
            expectedState: Session::get('bexio_oauth_state')
        );

        App::make(BexioOAuthAuthenticatonStoreResolver::class)
            ->put(authenticator: $authenticator);

        return Redirect::to(config('bexio.redirect_url', '/'));
    }
}
