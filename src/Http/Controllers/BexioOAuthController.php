<?php

namespace CodebarAg\Bexio\Http\Controllers;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;
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
        protected BexioOAuthTokenStore $tokenStore,
        protected BexioOAuthService $bexioOAuthService,
        protected BexioOAuthViewBuilder $bexioOAuthViewBuilder,
        protected BexioOAuthExceptionHandler $bexioOAuthExceptionHandler,
    ) {}

    /**
     * Resolve configuration for the current request using the IoC-bound resolver if present.
     *
     * This enables consuming apps to provide custom config logic (multi-tenant or otherwise)
     * by binding a resolver to 'bexio.config.resolver' in the service container.
     *
     * @param Request $request
     * @return ConfigWithCredentials
     */
    protected function resolveConfig(Request $request): ConfigWithCredentials
    {
        if (app()->bound('bexio.config.resolver')) {
            $resolver = app('bexio.config.resolver');
            return $resolver($request);
        }
        return new ConfigWithCredentials();
    }

    /**
     * Redirect the user to the Bexio authorization page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function redirect(Request $request)
    {
        if (! config('bexio.auth.use_oauth2')) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid Request',
                'OAuth2 is not enabled.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                403
            );
        }
        try {
            $config = $this->resolveConfig($request);
            $connector = new BexioConnector(
                configuration: $config
            );
            logger()->info('Bexio OAuth redirect', ['client_id' => $config->clientId, 'scopes' => $config->scopes]);

            $authorizationUrl = $connector->getAuthorizationUrl($config->scopes);

            $state = $connector->getState();
            Session::put("bexio_oauth_state:$state", $state);
            Session::put("bexio_oauth_config_id:$state", $config->identifier);

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
        $code = $request->input('code');

        if (! $state || ! $code) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid OAuth Callback',
                'Invalid OAuth callback parameters.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                400
            );
        }

        $expectedState = Session::pull("bexio_oauth_state:$state");
        $stateIdentifier = Session::pull("bexio_oauth_config_id:$state");

        if (! $expectedState || $expectedState !== $state) {
            return $this->bexioOAuthViewBuilder->build(
                'danger',
                'Invalid State',
                'The OAuth state parameter is invalid or expired.',
                ['url' => url('/'), 'label' => 'Back to Home'],
                null,
                400
            );
        }

        try {
            $config = $this->resolveConfig($request);

            if ($config->identifier !== $stateIdentifier) {
                throw new \RuntimeException('Configuration mismatch');
            }

            $connector = new BexioConnector(
                configuration: $config
            );

            $authenticator = $connector->getAccessToken($code, $state, $expectedState);
            if (! $authenticator) {
                throw new \RuntimeException('Failed to exchange authorization code for token');
            }
            $userinfo = $this->bexioOAuthService->fetchUserinfo($authenticator, $connector);
            $this->bexioOAuthService->verifyUserinfo($userinfo, $config->allowedEmails);

            $this->tokenStore->put($authenticator, $config->identifier);

            return $this->bexioOAuthViewBuilder->build(
                'success',
                'Successfully Connected!',
                'You have successfully connected your Bexio account.',
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
