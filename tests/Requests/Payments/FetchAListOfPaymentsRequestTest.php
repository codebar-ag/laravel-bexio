<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Payments\FetchAListOfPaymentsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Payments/fetch-a-list-of-payments.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfPaymentsRequest);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(FetchAListOfPaymentsRequest::class);
})->group('payments');
