<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Countries\FetchAListOfCountriesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/fetch-a-list-of-countries.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfCountriesRequest::class => MockResponse::fixture('Countries/fetch-a-list-of-countries'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $response = $connector->send(new FetchAListOfCountriesRequest);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(FetchAListOfCountriesRequest::class);
})->group('countries');
