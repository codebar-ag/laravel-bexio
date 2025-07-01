<?php

namespace CodebarAg\Bexio\Http\Controllers;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
        $redirectUrl = $this->connector()->getAuthorizationUrl();

        Session::put('bexio_oauth_state', $this->connector()->getState());

        return Redirect::away($redirectUrl);
    }

    /**
     * Handle Bexio OAuth2 callback, exchange code for tokens, and store them.
     *
     * @return string
     *
     * @throws Exception
     */
    public function callback(Request $request)
    {
        $authenticator = $this->connector()->getAccessToken(
            code: $request->get('code'),
            state: $request->get('state'),
            expectedState: Session::get('bexio_oauth_state')
        );

        $serialized = $authenticator->serialize();

        ray($serialized);

        return $authenticator->getAccessToken();
    }
}
