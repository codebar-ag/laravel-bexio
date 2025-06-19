<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OpenID\UserinfoDTO;
use CodebarAg\Bexio\Requests\OpenID\FetchUserinfoRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the userinfo request', function () {
    $mockClient = new MockClient([
        FetchUserinfoRequest::class => MockResponse::fixture('OpenID/fetch-userinfo'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);
    $response = $connector->send(new FetchUserinfoRequest);
    $mockClient->assertSent(FetchUserinfoRequest::class);

    // Assert DTO mapping
    $dto = (new FetchUserinfoRequest)->createDtoFromResponse($response);
    expect($dto)->toBeInstanceOf(UserinfoDTO::class);
    expect($dto->sub)->toBe('1b1bc12f-5a12-4f86-a631-ac3c4561eaeb');
    expect($dto->email)->toBe('john.doe@acme.com');
    expect($dto->company_name)->toBe('Acme Corp');
    expect($dto->email_verified)->toBeTrue();
});
