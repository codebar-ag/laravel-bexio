<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Exceptions\UserinfoVerificationException;
use CodebarAg\Bexio\Services\BexioOAuthService;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Illuminate\Support\Facades\Cache;
use Saloon\Exceptions\OAuthConfigValidationException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Response;

beforeEach(function () {
    Cache::flush();
    config()->set('bexio.auth.client_id', 'fake-client-id');
    config()->set('bexio.auth.client_secret', 'fake-client-secret');
    config()->set('bexio.auth.email', 'test@example.com');
});

it('redirects to Bexio authorization page successfully', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAuthorizationUrl')->andReturn('https://bexio.example/authorize');
        $mock->shouldReceive('getState')->andReturn('mocked-state');
    });
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertRedirect('https://bexio.example/authorize');
    $this->assertEquals('mocked-state', session('bexio_oauth_state'));
});

it('shows error view on OAuth config error during redirect', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAuthorizationUrl')->andThrow(new OAuthConfigValidationException('Config error'));
    });
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(500)->assertSee('OAuth Configuration Error');
});

it('shows error view on API error during redirect', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('status')->andReturn(500);
        $responseMock->shouldReceive('body')->andReturn('API error');
        $mock->shouldReceive('getAuthorizationUrl')->andThrow(new RequestException($responseMock));
    });
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(500)->assertSee('Bexio API Error');
});

it('shows error view on unauthorized error during redirect', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('status')->andReturn(401);
        $responseMock->shouldReceive('body')->andReturn('Unauthorized error');
        $mock->shouldReceive('getAuthorizationUrl')->andThrow(new UnauthorizedException($responseMock));
    });
    $response = $this->get('/bexio/oauth/redirect');
    $response->assertStatus(401)->assertSee('Bexio Authentication Error');
});

it('shows error view on unexpected error during redirect', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAuthorizationUrl')->andThrow(new Exception('Unexpected error'));
    });
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
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAccessToken')->andThrow(new Exception('Token error'));
    });
    $response = $this->get('/bexio/oauth/callback?code=abc&state=xyz');
    $response->assertStatus(400)->assertSee('Invalid OAuth Callback');
});

it('handles userinfo verification failure', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAccessToken')->andReturn('fake-authenticator');
    });
    $this->mock(BexioOAuthService::class, function ($mock) {
        $mock->shouldReceive('exchangeCodeForAuthenticator')->andReturn(new AccessTokenAuthenticator('fake-access-token'));
        $mock->shouldReceive('fetchUserinfo')->andReturn(['email' => 'fail@example.com']);
        $mock->shouldReceive('verifyUserinfo')->andThrow(new UserinfoVerificationException('Verification failed'));
    });
    $response = $this
        ->withSession(['bexio_oauth_state' => 'failure-state'])
        ->get('/bexio/oauth/callback?code=abc&state=failure-state');
    $response->assertStatus(403)->assertSee('Verification Failed');
});

it('stores authenticator and shows success', function () {
    $this->mock(BexioConnector::class, function ($mock) {
        $mock->shouldReceive('getAccessToken')->andReturn('fake-authenticator');
    });
    $this->mock(BexioOAuthService::class, function ($mock) {
        $mock->shouldReceive('exchangeCodeForAuthenticator')->andReturn(new AccessTokenAuthenticator('fake-access-token'));
        $mock->shouldReceive('fetchUserinfo')->andReturn(['email' => 'test@example.com']);
        $mock->shouldReceive('verifyUserinfo')->andReturnTrue();
    });
    $this->mock(BexioOAuthTokenStore::class, function ($mock) {
        $mock->shouldReceive('put')->once();
    });
    $response = $this
        ->withSession(['bexio_oauth_state' => 'success-state'])
        ->get('/bexio/oauth/callback?code=abc&state=success-state');
    $response->assertStatus(200)->assertSee('Bexio Connected!');
});
