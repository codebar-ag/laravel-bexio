<?php

use CodebarAg\Bexio\Requests\SingleTicketRequest;
use CodebarAg\Bexio\BexioConnector;
use Illuminate\Support\Facades\Config;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('will throw an exception if a subdomain is not set', closure: function () {
    Config::set('bexio.subdomain');
    $connector = new BexioConnector;
    $connector->resolveBaseUrl();

})->throws('No subdomain provided.', 500);

it('will not throw an exception if a subdomain is set', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
    ]);

    $connector = new BexioConnector;
    $connector->resolveBaseUrl();

})->expectNotToPerformAssertions();

it('will return the base path', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
    ]);

    $connector = new BexioConnector;
    $path = $connector->resolveBaseUrl();

    expect($path)->toBe('https://codebarsolutionsag.bexio.com/api/v2');

});

it('will throw an exception if an auth method is not set', closure: function () {
    config([
        'bexio.auth.method' => null,
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->throws('No authentication method provided.', 500);

it('will throw an exception if an auth method invalid', closure: function () {
    config([
        'bexio.auth.method' => 'not-a-valid-method',
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->throws('Invalid authentication method provided.', 500);

it('will not throw an exception if an auth method valid', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => 'test-token',
        'bexio.auth.password' => 'test-password',
    ]);

    config([
        'bexio.auth.method' => 'token',
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

    config([
        'bexio.auth.method' => 'basic',
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will throw an exception if a token is not provided when using the token method', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'token',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => null,
        'bexio.auth.password' => null,
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->throws('No API token provided for token authentication.', 500);

it('will not throw an exception if a token is provided when using the token method', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'token',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => 'test-token',
        'bexio.auth.password' => null,
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will throw an exception if a password is not provided when using the basic method', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'basic',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => null,
        'bexio.auth.password' => null,
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->throws('No password provided for basic authentication.', 500);

it('will not throw an exception if a password is provided when using the password method', closure: function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'basic',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => null,
        'bexio.auth.password' => 'test-password',
    ]);

    $connector = new BexioConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will compile the correct authentication string for token method', function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'token',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => 'test-token',
        'bexio.auth.password' => null,
    ]);

    $connector = new BexioConnector;

    $token = $connector->setAuth();

    expect($token)->toBe('test@example.com/token:test-token');
});

it('will compile the correct authentication string for basic method', function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsag',
        'bexio.auth.method' => 'basic',
        'bexio.auth.email_address' => 'test@example.com',
        'bexio.auth.api_token' => null,
        'bexio.auth.password' => 'test-password',
    ]);

    $connector = new BexioConnector;

    $token = $connector->setAuth();

    expect($token)->toBe('test@example.com:test-password');
});

it('will throw and authentication error when details are incorrect', function () {
    config([
        'bexio.subdomain' => 'codebarsolutionsagwrong',
        'bexio.auth.method' => 'basic',
        'bexio.auth.email_address' => 'tessdft@example.com',
        'bexio.auth.api_token' => null,
        'bexio.auth.password' => 'test-passwordsdfsf',
    ]);

    $mockClient = new MockClient([
        SingleTicketRequest::class => MockResponse::fixture('details-incorrect-request'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SingleTicketRequest(81));

    $mockClient->assertSent(SingleTicketRequest::class);

    expect($response->status())->toBe(404);
});
