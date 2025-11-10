<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Payments\FetchAListOfPaymentsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfPaymentsRequest);

    Saloon::assertSent(FetchAListOfPaymentsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
