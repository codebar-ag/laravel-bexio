<?php

use CodebarAg\Zendesk\Requests\SingleTicketRequest;
use CodebarAg\Zendesk\ZendeskConnector;
use Illuminate\Support\Facades\Config;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('will throw an exception if a subdomain is not set', closure: function () {
    Config::set('zendesk.subdomain');
    $connector = new ZendeskConnector;
    $connector->resolveBaseUrl();

})->throws('No subdomain provided.', 500);

it('will not throw an exception if a subdomain is set', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
    ]);

    $connector = new ZendeskConnector;
    $connector->resolveBaseUrl();

})->expectNotToPerformAssertions();

it('will return the base path', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
    ]);

    $connector = new ZendeskConnector;
    $path = $connector->resolveBaseUrl();

    expect($path)->toBe('https://codebarsolutionsag.zendesk.com/api/v2');

});

it('will throw an exception if an auth method is not set', closure: function () {
    config([
        'zendesk.auth.method' => null,
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->throws('No authentication method provided.', 500);

it('will throw an exception if an auth method invalid', closure: function () {
    config([
        'zendesk.auth.method' => 'not-a-valid-method',
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->throws('Invalid authentication method provided.', 500);

it('will not throw an exception if an auth method valid', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => 'test-token',
        'zendesk.auth.password' => 'test-password',
    ]);

    config([
        'zendesk.auth.method' => 'token',
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

    config([
        'zendesk.auth.method' => 'basic',
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will throw an exception if a token is not provided when using the token method', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'token',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => null,
        'zendesk.auth.password' => null,
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->throws('No API token provided for token authentication.', 500);

it('will not throw an exception if a token is provided when using the token method', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'token',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => 'test-token',
        'zendesk.auth.password' => null,
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will throw an exception if a password is not provided when using the basic method', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'basic',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => null,
        'zendesk.auth.password' => null,
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->throws('No password provided for basic authentication.', 500);

it('will not throw an exception if a password is provided when using the password method', closure: function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'basic',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => null,
        'zendesk.auth.password' => 'test-password',
    ]);

    $connector = new ZendeskConnector;
    $connector->setAuth();

})->expectNotToPerformAssertions();

it('will compile the correct authentication string for token method', function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'token',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => 'test-token',
        'zendesk.auth.password' => null,
    ]);

    $connector = new ZendeskConnector;

    $token = $connector->setAuth();

    expect($token)->toBe('test@example.com/token:test-token');
});

it('will compile the correct authentication string for basic method', function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsag',
        'zendesk.auth.method' => 'basic',
        'zendesk.auth.email_address' => 'test@example.com',
        'zendesk.auth.api_token' => null,
        'zendesk.auth.password' => 'test-password',
    ]);

    $connector = new ZendeskConnector;

    $token = $connector->setAuth();

    expect($token)->toBe('test@example.com:test-password');
});

it('will throw and authentication error when details are incorrect', function () {
    config([
        'zendesk.subdomain' => 'codebarsolutionsagwrong',
        'zendesk.auth.method' => 'basic',
        'zendesk.auth.email_address' => 'tessdft@example.com',
        'zendesk.auth.api_token' => null,
        'zendesk.auth.password' => 'test-passwordsdfsf',
    ]);

    $mockClient = new MockClient([
        SingleTicketRequest::class => MockResponse::fixture('details-incorrect-request'),
    ]);

    $connector = new ZendeskConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SingleTicketRequest(81));

    $mockClient->assertSent(SingleTicketRequest::class);

    expect($response->status())->toBe(404);
});
