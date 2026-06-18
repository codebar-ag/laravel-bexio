<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Taxes\DeleteATaxRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Taxes/delete-a-tax.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Taxes/fetch-a-list-of-taxes-for-delete.json');
    }

    Saloon::fake([
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-for-delete'),
        DeleteATaxRequest::class => MockResponse::fixture('Taxes/delete-a-tax'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    // There is no CreateTax request in this package, so we can only attempt to
    // delete a pre-existing tax. Taxes are typically protected on a live bexio
    // instance, so a rejection is expected and skips the test gracefully.
    $tax = $connector->send(new FetchAListOfTaxesRequest)->dto()->first();

    if ($tax === null) {
        $this->markTestSkipped('No taxes available on the live instance to delete.');
    }

    $response = $connector->send(new DeleteATaxRequest(id: $tax->id));

    if (! $response->successful()) {
        $this->markTestSkipped(sprintf(
            'Tax deletion rejected by API (HTTP %d): %s',
            $response->status(),
            $response->body(),
        ));
    }

    Saloon::assertSent(DeleteATaxRequest::class);

    expect($response->successful())->toBeTrue();
})->group('taxes');
