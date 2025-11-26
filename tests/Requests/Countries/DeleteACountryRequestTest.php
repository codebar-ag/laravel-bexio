<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Countries\DeleteACountryRequest;
use CodebarAg\Bexio\Requests\Countries\FetchAListOfCountriesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/delete-a-country.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/fetch-a-list-of-countries.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        DeleteACountryRequest::class => MockResponse::fixture('Countries/delete-a-country'),
        FetchAListOfCountriesRequest::class => MockResponse::fixture('Countries/fetch-a-list-of-countries'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $countriesResponse = $connector->send(new FetchAListOfCountriesRequest);
    $existingCountry = $countriesResponse->dto()->first();

    if (! $existingCountry) {
        $this->markTestSkipped('No countries found in the system to delete');
    }

    $response = $connector->send(new DeleteACountryRequest(country_id: $existingCountry->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteACountryRequest::class);
})->group('countries');
