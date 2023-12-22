<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\VatPeriods\FetchAListOfVatPeriodsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        //        FetchAListOfVatPeriodsRequest::class => MockResponse::fixture('VatPeriods/fetch-a-list-of-vat-periods'),
    ]);

    $connector = new BexioConnector;
    //    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfVatPeriodsRequest());

    $mockClient->assertSent(FetchAListOfVatPeriodsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
})->skip('No Values for testing');
