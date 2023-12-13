<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ManualEntries\GetNextReferenceNumberRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        GetNextReferenceNumberRequest::class => MockResponse::fixture('ManualEntries/get-next-reference-number'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetNextReferenceNumberRequest());

    $mockClient->assertSent(GetNextReferenceNumberRequest::class);
});
