<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\VatPeriods\VatPeriodDTO;
use CodebarAg\Bexio\Requests\VatPeriods\FetchAListOfVatPeriodsRequest;
use CodebarAg\Bexio\Requests\VatPeriods\FetchAVatPeriodRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfVatPeriodsRequest::class => MockResponse::make([
            [
                'id' => 1,
                'start' => '2018-01-01',
                'end' => '2018-03-31',
                'type' => 'quarter',
                'status' => 'closed',
                'closed_at' => '2018-04-28',
            ],
        ], 200),
        FetchAVatPeriodRequest::class => MockResponse::make([
            'id' => 1,
            'start' => '2018-01-01',
            'end' => '2018-03-31',
            'type' => 'quarter',
            'status' => 'closed',
            'closed_at' => '2018-04-28',
        ], 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $listResponse = $connector->send(new FetchAListOfVatPeriodsRequest);
    $vatPeriodId = $listResponse->dto()->first()->id;

    $response = $connector->send(new FetchAVatPeriodRequest(id: $vatPeriodId));

    $mockClient->assertSent(FetchAListOfVatPeriodsRequest::class);
    $mockClient->assertSent(FetchAVatPeriodRequest::class);

    expect($response->dto())->toBeInstanceOf(VatPeriodDTO::class);
});
