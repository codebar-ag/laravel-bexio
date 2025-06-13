<?php

use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Auth\AccessTokenAuthenticator;

beforeEach(function () {
    Cache::forget('bexio_oauth_authenticator');
});

it('returns null if cache is empty', function () {
    $store = new BexioOAuthTokenStore;
    expect($store->get())->toBeNull();
});

it('overwrites the authenticator', function () {
    $auth1 = new AccessTokenAuthenticator('token1', 'Bearer');
    $auth2 = new AccessTokenAuthenticator('token2', 'Bearer');
    $store = new BexioOAuthTokenStore;
    $store->put($auth1);
    expect($store->get())->toEqual($auth1);
    $store->put($auth2);
    expect($store->get())->toEqual($auth2);
});

it('stores and retrieves authenticator', function () {
    $auth = new AccessTokenAuthenticator('token', 'Bearer');
    $store = new BexioOAuthTokenStore;
    $store->put($auth);
    expect($store->get())->toEqual($auth);
});

it('forgets the authenticator', function () {
    $auth = new AccessTokenAuthenticator('token', 'Bearer');
    $store = new BexioOAuthTokenStore;
    $store->put($auth);
    $store->forget();
    expect($store->get())->toBeNull();
});
