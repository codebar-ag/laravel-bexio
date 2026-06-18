<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Taxes\TaxDTO;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchATaxRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Taxes/fetch-a-tax.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Taxes/fetch-a-list-of-taxes-for-fetch.json');
    }

    Saloon::fake([
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-for-fetch'),
        FetchATaxRequest::class => MockResponse::fixture('Taxes/fetch-a-tax'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $tax = $connector->send(new FetchAListOfTaxesRequest)->dto()->first();

    if ($tax === null) {
        $this->markTestSkipped('No taxes available on the live instance to fetch.');
    }

    $response = $connector->send(new FetchATaxRequest(id: $tax->id));

    Saloon::assertSent(FetchATaxRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(TaxDTO::class);
})->group('taxes');
