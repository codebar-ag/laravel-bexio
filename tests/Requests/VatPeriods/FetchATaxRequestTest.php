<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Taxes\FetchATaxRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchATaxRequest::class => MockResponse::fixture('VatPeriods/fetch-a-vat-periods'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchATaxRequest(id: 3));

    $mockClient->assertSent(FetchATaxRequest::class);
})->skip('No Values for testing');
