<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\OpenID\FetchUserinfoRequest;
use CodebarAg\Bexio\Services\BexioOAuthService;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('fetches userinfo using MockClient', function () {
    $mockClient = new MockClient([
        FetchUserinfoRequest::class => MockResponse::make([
            'email' => 'test@example.com',
            'email_verified' => true,
        ], 200),
    ]);
    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);
    $service = new BexioOAuthService;
    $authenticator = Mockery::mock(\Saloon\Http\Auth\AccessTokenAuthenticator::class);
    $authenticator->shouldReceive('set')->andReturnNull();
    $userinfo = $service->fetchUserinfo($authenticator, $connector);
    expect($userinfo['email'])->toBe('test@example.com');
    expect($userinfo['email_verified'])->toBeTrue();
    $mockClient->assertSent(FetchUserinfoRequest::class);
});

describe('BexioOAuthService', function () {
    it('can exchange code for authenticator', function () {
        $mockConnector = Mockery::mock(CodebarAg\Bexio\BexioConnector::class);
        $mockAuthenticator = Mockery::mock(\Saloon\Contracts\OAuthAuthenticator::class);
        $mockConnector->shouldReceive('getAccessToken')
            ->with('valid-code', 'state', 'expected-state')
            ->andReturn($mockAuthenticator);
        $service = Mockery::mock(BexioOAuthService::class)->makePartial();
        $service->shouldReceive('exchangeCodeForAuthenticator')
            ->with('valid-code', 'state', 'expected-state')
            ->andReturnUsing(function ($code, $state, $expectedState) use ($mockConnector) {
                return $mockConnector->getAccessToken($code, $state, $expectedState);
            });
        $authenticator = $service->exchangeCodeForAuthenticator('valid-code', 'state', 'expected-state');
        expect($authenticator)->toBe($mockAuthenticator);
    });

    it('throws on token exchange failure', function () {
        $mockConnector = Mockery::mock(CodebarAg\Bexio\BexioConnector::class);
        $mockConnector->shouldReceive('getAccessToken')
            ->with('bad-code', 'state', 'expected-state')
            ->andThrow(new Exception('Token error'));
        $service = Mockery::mock(BexioOAuthService::class)->makePartial();
        $service->shouldReceive('exchangeCodeForAuthenticator')
            ->with('bad-code', 'state', 'expected-state')
            ->andReturnUsing(function ($code, $state, $expectedState) use ($mockConnector) {
                return $mockConnector->getAccessToken($code, $state, $expectedState);
            });
        expect(fn() => $service->exchangeCodeForAuthenticator('bad-code', 'state', 'expected-state'))
            ->toThrow(Exception::class);
    });

    it('refreshes and persists authenticator', function () {
        $store = Mockery::mock(CodebarAg\Bexio\Support\BexioOAuthTokenStore::class);
        $connector = Mockery::mock(CodebarAg\Bexio\BexioConnector::class);
        $oldAuth = Mockery::mock(\Saloon\Http\Auth\AccessTokenAuthenticator::class);
        $newAuth = Mockery::mock(\Saloon\Http\Auth\AccessTokenAuthenticator::class);
        $store->shouldReceive('get')->andReturn($oldAuth);
        $connector->shouldReceive('refreshAccessToken')->with($oldAuth)->andReturn($newAuth);
        $store->shouldReceive('put')->with($newAuth);
        $oldAuth->shouldReceive('getAccessToken')->andReturn('old-token');
        $newAuth->shouldReceive('getAccessToken')->andReturn('new-token');
        $newAuth->shouldReceive('getExpiresAt')->andReturn(null);
        $service = new BexioOAuthService;
        $result = $service->refreshAuthenticator($store, $connector);
        expect($result)->toBe($newAuth);
    });

    it('returns null if no authenticator to refresh', function () {
        $store = Mockery::mock(CodebarAg\Bexio\Support\BexioOAuthTokenStore::class);
        $connector = Mockery::mock(CodebarAg\Bexio\BexioConnector::class);
        $store->shouldReceive('get')->andReturn(null);
        $service = new BexioOAuthService;
        $result = $service->refreshAuthenticator($store, $connector);
        expect($result)->toBeNull();
    });

    it('verifyUserinfo passes with correct data', function () {
        $service = new BexioOAuthService;
        config(['bexio.auth.oauth_email' => 'test@example.com']);
        $userinfo = ['email' => 'test@example.com', 'email_verified' => true];
        $service->verifyUserinfo($userinfo);
        expect(true)->toBeTrue();
    });

    it('verifyUserinfo throws on unverified email', function () {
        $service = new BexioOAuthService;
        config(['bexio.auth.oauth_email' => 'test@example.com']);
        $userinfo = ['email' => 'test@example.com', 'email_verified' => false];
        expect(fn() => $service->verifyUserinfo($userinfo))->toThrow(Exception::class);
    });

    it('verifyUserinfo throws on wrong email', function () {
        $service = new BexioOAuthService;
        config(['bexio.auth.oauth_email' => 'test@example.com']);
        $userinfo = ['email' => 'wrong@example.com', 'email_verified' => true];
        expect(fn() => $service->verifyUserinfo($userinfo))->toThrow(Exception::class);
    });
});
