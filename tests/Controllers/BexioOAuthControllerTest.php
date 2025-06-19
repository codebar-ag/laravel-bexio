<?php

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    Cache::flush();
    config()->set('bexio.auth.use_oauth2', true);
    config()->set('bexio.auth.oauth2.client_id', 'fake-client-id');
    config()->set('bexio.auth.oauth2.client_secret', 'fake-client-secret');
    config()->set('bexio.auth.oauth2.allowed_emails', ['test@example.com', 'test2@example.com']);
});

it('redirects to Bexio authorization page and sets session state', function () {
    $response = $this->get('/bexio/oauth/redirect');

    // Assert the response is a redirect to the Bexio auth endpoint (you can use assertRedirectContains if available)
    $response->assertRedirect();
    $redirectUrl = $response->headers->get('Location');
    expect($redirectUrl)->toContain('auth.bexio.com/realms/bexio/protocol/openid-connect/auth');

    // Assert the session contains the OAuth state (the key is dynamic, so check for any matching key)
    $sessionKeys = array_keys(session()->all());
    $stateKey = collect($sessionKeys)->first(fn($key) => str_starts_with($key, 'bexio_oauth_state:'));
    expect($stateKey)->not->toBeNull();
    expect(session($stateKey))->not->toBeNull();
});

it('shows error view on OAuth config error during redirect', function () {
    config()->set('bexio.auth.oauth2.client_id', null);
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(500)->assertSee('OAuth Configuration Error');
});

it('shows error view on OAuth2 disabled during redirect', function () {
    // Simulate API error by disabling OAuth2 (triggers UnauthorizedHttpException in controller)
    config()->set('bexio.auth.use_oauth2', false);
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(403)->assertSee('OAuth2 is not enabled');
});

it('shows error view on unauthorized error during redirect', function () {
    // Simulate unauthorized error by setting invalid client secret (causes OAuthConfigValidationException)
    config()->set('bexio.auth.oauth2.client_id', 'fake-client-id');
    config()->set('bexio.auth.oauth2.client_secret', null);
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(500)->assertSee('OAuth Configuration Error');
});

it('shows error view on unexpected error during redirect', function () {
    // Simulate unexpected error by throwing a generic exception via a test double controller
    // For demonstration, set an invalid config value that triggers an uncaught exception
    config()->set('bexio.auth.oauth2.client_id', 'fake-client-id');
    config()->set('bexio.auth.oauth2.client_secret', 'fake-client-secret');
    // Simulate by passing an invalid type for scopes
    config()->set('bexio.auth.oauth2.scopes', 'invalid-scope-type');
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(500)->assertSee('OAuth Error');
});

it('shows cancellation view when user rejects authorization', function () {
    $response = $this->get('/bexio/oauth/callback?error=access_denied');
    $response->assertStatus(200)
        ->assertSee('Bexio Connection Cancelled')
        ->assertSee('You cancelled connecting your Bexio account.');
});

it('handles missing code/state in callback', function () {
    $response = $this->get('/bexio/oauth/callback');
    $response->assertStatus(400)->assertSee('Invalid OAuth Callback');
});

it('handles token exchange failure', function () {
    MockClient::global([
        MockResponse::make(['error' => 'invalid_grant'], 400),
    ]);

    $clientId = config('bexio.auth.oauth2.client_id');
    $clientSecret = config('bexio.auth.oauth2.client_secret');
    $identifier = hash('sha256', "{$clientId}{$clientSecret}");

    $response = $this
        ->withSession([
            'bexio_oauth_state:xyz' => 'xyz',
            'bexio_oauth_config_id:xyz' => $identifier,
        ])
        ->get('/bexio/oauth/callback?code=abc&state=xyz');
    $response->assertStatus(400)->assertSee('Invalid OAuth Callback');
});

it('handles userinfo verification failure', function () {

    // Mock token and userinfo endpoints
    MockClient::global([
        MockResponse::make([
            'access_token' => 'fake-access-token',
            'refresh_token' => 'fake-refresh-token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
            'id_token' => 'fake-id-token',
        ]),
        MockResponse::make([
            'email' => 'fail@example.com',
        ]),
    ]);

    $clientId = config('bexio.auth.oauth2.client_id');
    $clientSecret = config('bexio.auth.oauth2.client_secret');
    $identifier = hash('sha256', "{$clientId}{$clientSecret}");

    $response = $this
        ->withSession([
            'bexio_oauth_state:failure-state' => 'failure-state',
            'bexio_oauth_config_id:failure-state' => $identifier,
        ])
        ->get('/bexio/oauth/callback?code=abc&state=failure-state');

    $response->assertStatus(403)->assertSee('Verification Failed');
});

it('stores authenticator and shows success', function () {
    MockClient::global([
        MockResponse::make([
            'access_token' => 'fake-access-token',
            'refresh_token' => 'fake-refresh-token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
            'id_token' => 'fake-id-token',
        ]),
        MockResponse::make([
            'email' => 'test@example.com',
            'email_verified' => true,
        ]),
    ]);

    $clientId = config('bexio.auth.oauth2.client_id');
    $clientSecret = config('bexio.auth.oauth2.client_secret');
    $identifier = hash('sha256', "{$clientId}{$clientSecret}");

    $response = $this
        ->withSession([
            'bexio_oauth_state:success-state' => 'success-state',
            'bexio_oauth_config_id:success-state' => $identifier,
        ])
        ->get('/bexio/oauth/callback?code=abc&state=success-state');
    $response->assertStatus(200)->assertSee('Successfully Connected!');
});
