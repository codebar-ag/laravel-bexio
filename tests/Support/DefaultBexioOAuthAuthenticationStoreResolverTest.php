<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticatonStoreResolver;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\OAuth2\GetRefreshTokenRequest;
use Saloon\Laravel\Saloon;

beforeEach(function () {
    $this->resolver = App::make(BexioOAuthAuthenticatonStoreResolver::class);
    Cache::flush();
});

afterEach(function () {
    // Reset Saloon fakes after each test
    Saloon::fake([]);

    // Reset any container bindings that might have been mocked
    App::forgetInstance(\CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver::class);
    App::forgetInstance(\CodebarAg\Bexio\BexioConnector::class);
});

it('returns null when no authenticator is cached', function () {
    $result = $this->resolver->get();

    expect($result)->toBeNull();
});

it('can store and retrieve an authenticator', function () {
    $authenticator = new AccessTokenAuthenticator('test_token');

    $this->resolver->put($authenticator);
    $retrieved = $this->resolver->get();

    expect($retrieved)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($retrieved->accessToken)->toBe('test_token');
});

it('encrypts the authenticator when storing', function () {
    $authenticator = new AccessTokenAuthenticator('test_token');

    $this->resolver->put($authenticator);

    $cachedValue = Cache::get('bexio_oauth_authenticator');
    expect($cachedValue)->not()->toContain('test_token'); // Should be encrypted

    // Should be able to decrypt
    $decrypted = Crypt::decrypt($cachedValue);
    expect($decrypted)->toBeString();
});

it('returns null when cached data cannot be decrypted', function () {
    // Store invalid encrypted data
    Cache::put('bexio_oauth_authenticator', 'invalid_encrypted_data');

    $result = $this->resolver->get();

    expect($result)->toBeNull();
});

it('can forget cached authenticator', function () {
    $authenticator = new AccessTokenAuthenticator('test_token');

    $this->resolver->put($authenticator);
    expect($this->resolver->get())->not()->toBeNull();

    $this->resolver->forget();
    expect($this->resolver->get())->toBeNull();
});

it('refreshes expired token automatically', function () {
    // Set up OAuth configuration in config
    config([
        'bexio.auth.oauth.client_id' => 'test_client_id',
        'bexio.auth.oauth.client_secret' => 'test_client_secret',
        'bexio.auth.oauth.redirect_uri' => 'http://localhost/callback',
        'bexio.auth.oauth.scopes' => ['openid', 'profile'],
    ]);

    // Create an expired authenticator
    $expiredAuthenticator = new AccessTokenAuthenticator(
        'expired_token',
        'refresh_token',
        (new \DateTimeImmutable)->modify('-1 hour') // Expired 1 hour ago
    );

    // Store the expired authenticator
    $this->resolver->put($expiredAuthenticator);

    // Mock the HTTP requests
    Saloon::fake([
        // Mock the OpenID configuration request (needed for BexioConnector constructor)
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),

        // Mock the refresh token request
        GetRefreshTokenRequest::class => MockResponse::make([
            'access_token' => 'fresh_token',
            'refresh_token' => 'new_refresh_token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ], 200),
    ]);

    // Call get() which should detect expiration and refresh
    $result = $this->resolver->get();

    // Verify we got the fresh authenticator
    expect($result)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($result->accessToken)->toBe('fresh_token')
        ->and($result->refreshToken)->toBe('new_refresh_token')
        ->and($result->hasExpired())->toBeFalse();

    // Verify the fresh authenticator was stored back in cache
    $cachedResult = $this->resolver->get();
    expect($cachedResult->accessToken)->toBe('fresh_token');

    // Verify the expected HTTP requests were made
    Saloon::assertSent(OpenIDConfigurationRequest::class);
    Saloon::assertSent(GetRefreshTokenRequest::class);
});

it('uses configured cache store', function () {
    config(['bexio.cache_store' => 'array']);

    $authenticator = new AccessTokenAuthenticator('test_token');
    $resolver = App::make(BexioOAuthAuthenticatonStoreResolver::class);

    $resolver->put($authenticator);
    $retrieved = $resolver->get();

    expect($retrieved)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($retrieved->accessToken)->toBe('test_token');
});

it('falls back to default cache store when bexio cache store is not configured', function () {
    config(['bexio.cache_store' => null]);
    config(['cache.default' => 'array']);

    $authenticator = new AccessTokenAuthenticator('test_token');
    $resolver = App::make(BexioOAuthAuthenticatonStoreResolver::class);

    $resolver->put($authenticator);
    $retrieved = $resolver->get();

    expect($retrieved)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($retrieved->accessToken)->toBe('test_token');
});

it('handles serialization and unserialization correctly', function () {
    $originalAuthenticator = new AccessTokenAuthenticator(
        'test_token',
        'refresh_token',
        (new \DateTimeImmutable)->modify('+1 hour')
    );

    $this->resolver->put($originalAuthenticator);
    $retrieved = $this->resolver->get();

    expect($retrieved)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($retrieved->accessToken)->toBe('test_token')
        ->and($retrieved->refreshToken)->toBe('refresh_token')
        ->and($retrieved->hasExpired())->toBeFalse();
});
