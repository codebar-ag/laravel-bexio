<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\DocumentSettings\FetchAListOfDocumentSettingsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfDocumentSettingsRequest::class => MockResponse::fixture('DocumentSettings/fetch-a-list-of-document-settings'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfDocumentSettingsRequest);

    ray($response->dto());

    $mockClient->assertSent(FetchAListOfDocumentSettingsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(10);
})->only();
