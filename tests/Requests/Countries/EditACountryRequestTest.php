<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use CodebarAg\Bexio\Dto\Countries\CreateEditCountryDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Countries\EditACountryRequest;
use CodebarAg\Bexio\Requests\Countries\FetchAListOfCountriesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/edit-a-country.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/fetch-a-list-of-countries.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        EditACountryRequest::class => MockResponse::fixture('Countries/edit-a-country'),
        FetchAListOfCountriesRequest::class => MockResponse::fixture('Countries/fetch-a-list-of-countries'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $countriesResponse = $connector->send(new FetchAListOfCountriesRequest);
    $existingCountry = $countriesResponse->dto()->first();

    if (! $existingCountry) {
        $this->markTestSkipped('No countries found in the system to edit');
    }

    $response = $connector->send(new EditACountryRequest(
        country_id: $existingCountry->id,
        data: new CreateEditCountryDTO(
            name: 'Updated Country Name',
            name_short: $existingCountry->name_short,
            iso3166_alpha2: $existingCountry->iso3166_alpha2
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(CountryDTO::class);

    Saloon::assertSent(EditACountryRequest::class);
})->group('countries');
