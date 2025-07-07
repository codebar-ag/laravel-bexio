<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\VatPeriods\FetchAListOfVatPeriodsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
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
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfVatPeriodsRequest);

    Saloon::assertSent(FetchAListOfVatPeriodsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class);
});
