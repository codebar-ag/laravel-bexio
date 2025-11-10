<?php

use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;
use Illuminate\Support\Facades\App;

it('resolves to a ConnectWithOAuth instance', function () {
    config([
        'bexio.auth.oauth.client_id' => 'test_client_id',
        'bexio.auth.oauth.client_secret' => 'test_client_secret',
    ]);

    $resolver = App::make(BexioOAuthConfigResolver::class);

    $result = $resolver->resolve();

    expect($result)->toBeInstanceOf(ConnectWithOAuth::class);
});

it('returns a new instance each time', function () {
    config([
        'bexio.auth.oauth.client_id' => 'test_client_id',
        'bexio.auth.oauth.client_secret' => 'test_client_secret',
    ]);

    $resolver = App::make(BexioOAuthConfigResolver::class);

    $first = $resolver->resolve();
    $second = $resolver->resolve();

    expect($first)->toBeInstanceOf(ConnectWithOAuth::class)
        ->and($second)->toBeInstanceOf(ConnectWithOAuth::class)
        ->and($first)->not()->toBe($second); // Different instances
});

it('returns ConnectWithOAuth with config values', function () {
    config([
        'bexio.auth.oauth.client_id' => 'test_client_id',
        'bexio.auth.oauth.client_secret' => 'test_client_secret',
        'bexio.auth.oauth.scopes' => ['openid', 'profile'],
    ]);

    $resolver = App::make(BexioOAuthConfigResolver::class);

    $result = $resolver->resolve();

    expect($result)->toBeInstanceOf(ConnectWithOAuth::class)
        ->and($result->client_id)->toBe('test_client_id')
        ->and($result->client_secret)->toBe('test_client_secret')
        ->and($result->redirect_uri)->toBe(route('bexio.oauth.callback'))
        ->and($result->scopes)->toBe(['openid', 'profile']);
});

it('throws exception when required config is missing', function () {
    config([
        'bexio.auth.oauth.client_id' => null,
        'bexio.auth.oauth.client_secret' => null,
    ]);

    $resolver = App::make(BexioOAuthConfigResolver::class);

    expect(fn () => $resolver->resolve())
        ->toThrow(\Exception::class, 'Client ID is required.');
});
