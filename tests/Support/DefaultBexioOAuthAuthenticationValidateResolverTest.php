<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationValidateResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\BexioOAuthAuthenticationValidationResult;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

beforeEach(function () {
    $this->resolver = App::make(BexioOAuthAuthenticationValidateResolver::class);
    
    // Mock the OpenID configuration request needed for BexioConnector
    Saloon::fake([
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
    ]);
});

afterEach(function () {
    Saloon::fake([]);
});

it('always returns success result for any connector', function () {
    $configuration = new ConnectWithOAuth(
        'test_client_id',
        'test_client_secret',
        'http://localhost/callback',
        ['openid']
    );
    
    $connector = new BexioConnector($configuration, autoResolveAndAuthenticate: false);
    $authenticator = new AccessTokenAuthenticator('test_token');
    $connector->authenticate($authenticator);

    $result = $this->resolver->resolve($connector);

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeTrue()
        ->and($result->redirect)->toBeNull();
});

it('returns success result for connector with expired authenticator', function () {
    $configuration = new ConnectWithOAuth(
        'test_client_id',
        'test_client_secret',
        'http://localhost/callback',
        ['openid']
    );
    
    $connector = new BexioConnector($configuration, autoResolveAndAuthenticate: false);
    $expiredAuthenticator = new AccessTokenAuthenticator(
        'expired_token',
        'refresh_token',
        (new \DateTimeImmutable)->modify('-1 hour') // Expired 1 hour ago
    );
    $connector->authenticate($expiredAuthenticator);

    $result = $this->resolver->resolve($connector);

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeTrue()
        ->and($result->redirect)->toBeNull();
});

it('returns success result for connector with authenticator without refresh token', function () {
    $configuration = new ConnectWithOAuth(
        'test_client_id',
        'test_client_secret',
        'http://localhost/callback',
        ['openid']
    );
    
    $connector = new BexioConnector($configuration, autoResolveAndAuthenticate: false);
    $authenticator = new AccessTokenAuthenticator('test_token');
    $connector->authenticate($authenticator);

    $result = $this->resolver->resolve($connector);

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeTrue()
        ->and($result->redirect)->toBeNull();
});

it('can create success result using static method', function () {
    $result = BexioOAuthAuthenticationValidationResult::success();

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeTrue()
        ->and($result->redirect)->toBeNull();
});

it('can create failed result without custom redirect', function () {
    $result = BexioOAuthAuthenticationValidationResult::failed();

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeFalse()
        ->and($result->redirect)->toBeNull();
});

it('can create failed result with custom redirect', function () {
    $customRedirect = Redirect::to('/custom-error-page')
        ->with('error', 'Custom validation failed');

    $result = BexioOAuthAuthenticationValidationResult::failed($customRedirect);

    expect($result)->toBeInstanceOf(BexioOAuthAuthenticationValidationResult::class)
        ->and($result->isValid)->toBeFalse()
        ->and($result->redirect)->toBeInstanceOf(RedirectResponse::class)
        ->and($result->redirect)->toBe($customRedirect);
}); 