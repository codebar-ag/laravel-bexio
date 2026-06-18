<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Currencies\CreateCurrencyDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\CreateCurrencyRequest;
use CodebarAg\Bexio\Requests\Currencies\DeleteACurrencyRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAllPossibleCurrencyCodesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Currencies/delete-a-currency.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Currencies/create-a-currency-for-delete.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Currencies/fetch-a-list-of-currencies-for-delete.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Currencies/fetch-currency-codes-for-delete.json');
    }

    Saloon::fake([
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies-for-delete'),
        FetchAllPossibleCurrencyCodesRequest::class => MockResponse::fixture('Currencies/fetch-currency-codes-for-delete'),
        CreateCurrencyRequest::class => MockResponse::fixture('Currencies/create-a-currency-for-delete'),
        DeleteACurrencyRequest::class => MockResponse::fixture('Currencies/delete-a-currency'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    // The currency name must be a valid ISO code that is not already registered
    // on the instance. Pick the first available unused code at runtime.
    $existing = collect($connector->send(new FetchAListOfCurrenciesRequest)->dto())
        ->map(fn ($currency) => $currency->name)
        ->all();

    $codes = collect($connector->send(new FetchAllPossibleCurrencyCodesRequest)->dto());

    $code = $codes->first(fn ($candidate) => ! in_array($candidate, $existing, true));

    if ($code === null) {
        $this->markTestSkipped('No unused currency code available to create a deletable currency.');
    }

    $created = $connector->send(new CreateCurrencyRequest(
        data: new CreateCurrencyDTO(
            name: $code,
            round_factor: 0.05,
        ),
    ))->dto();

    $response = $connector->send(new DeleteACurrencyRequest(id: $created->id));

    Saloon::assertSent(DeleteACurrencyRequest::class);

    expect($response->successful())->toBeTrue();
})->group('currencies');
