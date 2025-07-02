<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticatonStoreResolver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Saloon\Http\Auth\AccessTokenAuthenticator;

beforeEach(function () {
    $this->resolver = App::make(BexioOAuthAuthenticatonStoreResolver::class);
    Cache::flush();
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

// TODO: This test needs better mocking to avoid actual HTTP requests
// it('refreshes expired token automatically', function () {
//     // This test is commented out because it causes stack overflow due to actual HTTP requests
//     // in the BexioConnector constructor when trying to get OAuth configuration
// });

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
