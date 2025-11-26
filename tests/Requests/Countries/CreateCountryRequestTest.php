<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use CodebarAg\Bexio\Dto\Countries\CreateEditCountryDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Countries\CreateCountryRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/create-country.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CreateCountryRequest::class => MockResponse::fixture('Countries/create-country'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateCountryRequest(
        new CreateEditCountryDTO(
            name: 'Test Country',
            name_short: 'TC',
            iso3166_alpha2: 'TC'
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(CountryDTO::class);

    Saloon::assertSent(CreateCountryRequest::class);
})->group('countries');
