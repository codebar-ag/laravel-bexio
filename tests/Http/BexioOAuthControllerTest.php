<?php

use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Saloon\Exceptions\InvalidStateException;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\OAuth2\GetAccessTokenRequest;
use Saloon\Laravel\Saloon;

beforeEach(function () {
    MockClient::destroyGlobal();

    config()->set('bexio.auth.oauth.client_id', 'client-id');
    config()->set('bexio.auth.oauth.client_secret', 'client-secret');
    config()->set('bexio.redirect_url', '/done');

    Saloon::fake([
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
        GetAccessTokenRequest::class => MockResponse::make([
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'expires_in' => 3600,
        ]),
    ]);
});

it('stores the state that is sent to bexio in the authorization url', function () {
    $response = $this->get(route('bexio.oauth.redirect'));

    $response->assertRedirect();

    parse_str((string) parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);

    expect($query['state'] ?? null)
        ->toBeString()
        ->not->toBeEmpty()
        ->toBe(session('bexio_oauth_state'));
});

it('rejects the callback when no state was stored in the session', function () {
    $this->get(route('bexio.oauth.callback', ['code' => 'code', 'state' => 'forged']))
        ->assertRedirect('/done')
        ->assertSessionHas('bexio_oauth_success', false);

    Saloon::assertNotSent(GetAccessTokenRequest::class);
});

it('rejects the callback when the state does not match', function () {
    $this->withoutExceptionHandling();

    $this->withSession(['bexio_oauth_state' => 'expected'])
        ->get(route('bexio.oauth.callback', ['code' => 'code', 'state' => 'forged']));
})->throws(InvalidStateException::class);

it('exchanges the code and forgets the state when the state matches', function () {
    $this->withSession(['bexio_oauth_state' => 'expected'])
        ->get(route('bexio.oauth.callback', ['code' => 'code', 'state' => 'expected']))
        ->assertRedirect('/done')
        ->assertSessionHas('bexio_oauth_success', true)
        ->assertSessionMissing('bexio_oauth_state');

    Saloon::assertSent(GetAccessTokenRequest::class);

    $authenticator = app(BexioOAuthAuthenticationStoreResolver::class)->get();

    expect($authenticator)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($authenticator->getAccessToken())->toBe('access-token');
});
