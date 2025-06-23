<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Exceptions\UserinfoVerificationException;
use CodebarAg\Bexio\Services\BexioOAuthService;
use CodebarAg\Bexio\Support\BexioOAuthTokenStore;
use Saloon\Http\Auth\AccessTokenAuthenticator;

describe('BexioOAuthService', function () {
    it('verifyUserinfo passes with verified, allowed email', function () {
        $service = new BexioOAuthService;
        $userinfo = ['email' => 'test@example.com', 'email_verified' => true];
        $allowed = ['test@example.com', 'test2@example.com'];
        $service->verifyUserinfo($userinfo, $allowed);
        expect(true)->toBeTrue();
    });

    it('verifyUserinfo throws if email not verified', function () {
        $service = new BexioOAuthService;
        $userinfo = ['email' => 'test@example.com', 'email_verified' => false];
        $allowed = ['test@example.com'];
        expect(fn () => $service->verifyUserinfo($userinfo, $allowed))
            ->toThrow(UserinfoVerificationException::class, 'Bexio account email must be verified.');
    });

    it('verifyUserinfo throws if email missing', function () {
        $service = new BexioOAuthService;
        $userinfo = ['email_verified' => true];
        $allowed = ['test@example.com'];
        expect(fn () => $service->verifyUserinfo($userinfo, $allowed))
            ->toThrow(UserinfoVerificationException::class, 'No email address provided by Bexio account.');
    });

    it('verifyUserinfo throws if allowedEmails is empty', function () {
        $service = new BexioOAuthService;
        $userinfo = ['email' => 'test@example.com', 'email_verified' => true];
        $allowed = [];
        expect(fn () => $service->verifyUserinfo($userinfo, $allowed))
            ->toThrow(UserinfoVerificationException::class, 'No allowed emails configured.');
    });

    it('verifyUserinfo throws if email not in allowed list', function () {
        $service = new BexioOAuthService;
        $userinfo = ['email' => 'notallowed@example.com', 'email_verified' => true];
        $allowed = ['test@example.com'];
        expect(fn () => $service->verifyUserinfo($userinfo, $allowed))
            ->toThrow(UserinfoVerificationException::class, 'Email address notallowed@example.com is not authorized to connect this Bexio account.');
    });

    it('getValidAuthenticator returns null if no authenticator found', function () {
        $tokenStore = Mockery::mock(BexioOAuthTokenStore::class);
        $connector = Mockery::mock(BexioConnector::class);
        $tokenStore->shouldReceive('get')->andReturn(null);
        $service = new BexioOAuthService;
        $result = $service->getValidAuthenticator($tokenStore, $connector);
        expect($result)->toBeNull();
    });

    it('getValidAuthenticator refreshes expired authenticator', function () {
        $tokenStore = Mockery::mock(BexioOAuthTokenStore::class);
        $connector = Mockery::mock(BexioConnector::class);
        $expiredAuth = Mockery::mock(AccessTokenAuthenticator::class);
        $newAuth = Mockery::mock(AccessTokenAuthenticator::class);
        $tokenStore->shouldReceive('get')->andReturn($expiredAuth);
        $expiredAuth->shouldReceive('hasExpired')->andReturn(true);
        $connector->shouldReceive('refreshAccessToken')->with($expiredAuth)->andReturn($newAuth);
        $tokenStore->shouldReceive('put')->with($newAuth, null);
        $service = new BexioOAuthService;
        $result = $service->getValidAuthenticator($tokenStore, $connector);
        expect($result)->toBe($newAuth);
    });

    it('getValidAuthenticator returns authenticator if not expired', function () {
        $tokenStore = Mockery::mock(BexioOAuthTokenStore::class);
        $connector = Mockery::mock(BexioConnector::class);
        $auth = Mockery::mock(AccessTokenAuthenticator::class);
        $tokenStore->shouldReceive('get')->andReturn($auth);
        $auth->shouldReceive('hasExpired')->andReturn(false);
        $service = new BexioOAuthService;
        $result = $service->getValidAuthenticator($tokenStore, $connector);
        expect($result)->toBe($auth);
    });
});
