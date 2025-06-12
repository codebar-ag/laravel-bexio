<?php

namespace CodebarAg\Bexio\Http\Controllers;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Services\BexioOAuthService;
use CodebarAg\Bexio\Support\BexioOAuthExceptionHandler;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use CodebarAg\Bexio\Support\BexioOAuthViewBuilder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BexioOAuthController extends Controller
{
    /**
     * Inject dependencies for OAuth, token storage, connector, exception handler, and view builder.
     */
    public function __construct(
        private BexioOAuthService $bexioOAuthService,
        private BexioOAuthTokenStore $bexioTokenStore,
        private BexioConnector $bexioConnector,
        private BexioOAuthExceptionHandler $bexioOAuthExceptionHandler,
        private BexioOAuthViewBuilder $bexioOAuthViewBuilder,
    ) {}

    /**
     * Redirect the user to the Bexio authorization page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function redirect()
    {
        try {
            $connector = $this->bexioConnector;
            $appScopes = config('bexio.auth.scopes', []);
            $authorizationUrl = $connector->getAuthorizationUrl($appScopes);
            Session::put('bexio_oauth_state', $connector->getState());

            return Redirect::away($authorizationUrl);
        } catch (\Throwable $e) {
            return $this->bexioOAuthExceptionHandler->render($e, 'redirect');
        }
    }

    /**
     * Handle Bexio OAuth2 callback, exchange code for tokens, and store them.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function callback(Request $request)
    {
        if ($view = $this->handleBexioCallbackError($request)) {
            return $view;
        }

        $state = $request->input('state');
        $expectedState = Session::pull('bexio_oauth_state');
        $code = $request->input('code');

        if (! $code || ! $state || ! $expectedState) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid OAuth Callback',
                'Missing or invalid authorization code/state. Please start the connection process again.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                400
            );
        }

        try {
            $authenticator = $this->bexioOAuthService->exchangeCodeForAuthenticator($code, $state, $expectedState);
        } catch (\Throwable $e) {
            return $this->bexioOAuthExceptionHandler->render($e, 'callback');
        }

        try {
            $connector = $this->bexioConnector;
            $userinfo = $this->bexioOAuthService->fetchUserinfo($authenticator, $connector);
            $this->bexioOAuthService->verifyUserinfo($userinfo);
            $this->bexioTokenStore->put($authenticator);

            return $this->bexioOAuthViewBuilder->build(
                'success',
                'Bexio Connected!',
                'Your Bexio account was successfully connected.',
                ['url' => url('/'), 'label' => 'Back to Home']
            );
        } catch (\Throwable $e) {
            return $this->bexioOAuthExceptionHandler->render($e, 'callback');
        }
    }

    /**
     * Handle errors returned by Bexio during the OAuth callback/redirect process.
     *
     * This method processes error responses from Bexio when a user is redirected back to the application
     * after approving or denying the OAuth authorization request. If the user cancels or Bexio returns an error,
     * this method renders an appropriate user-facing error view.
     */
    private function handleBexioCallbackError(Request $request): \Illuminate\View\View|\Illuminate\Http\Response|null
    {
        if ($request->has('error')) {
            $error = $request->input('error');

            if ($error === 'access_denied') {
                return $this->bexioOAuthViewBuilder->build(
                    'warning',
                    'Bexio Connection Cancelled',
                    'You cancelled connecting your Bexio account.',
                    null,
                    [
                        [
                            'url' => url('/'),
                            'label' => 'Back to Home',
                            'class' => 'secondary',
                        ],
                        [
                            'url' => route('bexio.oauth.redirect'),
                            'label' => 'Try Again',
                            'class' => 'primary',
                        ],
                    ],
                    200
                );
            }
            $description = $request->input('error_description', 'Authorization was denied or failed.');
            $status = $request->input('error') === 'access_denied' ? 400 : 500;

            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Bexio OAuth2 Error',
                $description,
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                $status
            );
        }

        return null;
    }
}
