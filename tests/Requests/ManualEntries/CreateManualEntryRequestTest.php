<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ManualEntries\CreateEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\ManualEntryDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Enums\ManualEntries\TypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\ManualEntries\CreateManualEntryRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        CreateManualEntryRequest::class => MockResponse::fixture('ManualEntries/create-manual-entry'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $tax = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'))->dto()->first();
    $accounts = $connector->send(new FetchAListOfAccountsRequest)->dto();
    $currency = $connector->send(new FetchAListOfCurrenciesRequest)->dto()->first();

    $debitAccount = $accounts->first();
    $creditAccount = $accounts->skip(1)->first();

    if (! $tax || ! $debitAccount || ! $creditAccount || ! $currency) {
        $this->markTestSkipped('A tax, two accounts and a currency are required to create a manual entry.');
    }

    $response = $connector->send(new CreateManualEntryRequest(
        new CreateManualEntryDTO(
            type: TypeEnum::MANUAL_SINGLE_ENTRY(),
            date: '2026-06-01',
            entries: collect([
                new CreateEntryDTO(
                    tax_id: $tax->id,
                    tax_account_id: $debitAccount->id,
                    description: 'Test manual entry',
                    amount: 100,
                    currency_id: $currency->id,
                    currency_factor: 1,
                    debit_account_id: $debitAccount->id,
                    credit_account_id: $creditAccount->id,
                ),
            ]),
            reference_nr: (string) now()->timestamp,
        )
    ));

    if ($response->status() === 422 && str_contains((string) $response->body(), 'fiscal year')) {
        $this->markTestSkipped('Base VAT setting cannot be created because no fiscal year exists.');
    }

    Saloon::assertSent(CreateManualEntryRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ManualEntryDTO::class);
});
