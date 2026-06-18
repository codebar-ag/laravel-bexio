<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ManualEntries\AddFileDTO;
use CodebarAg\Bexio\Dto\ManualEntries\CreateEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\FileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Enums\ManualEntries\TypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\ManualEntries\AddFileToAccountingEntryLineRequest;
use CodebarAg\Bexio\Requests\ManualEntries\CreateManualEntryRequest;
use CodebarAg\Bexio\Requests\ManualEntries\FetchFileOfAccountingEntryLineRequest;
use CodebarAg\Bexio\Requests\ManualEntries\FetchFilesOfAccountingEntryRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        CreateManualEntryRequest::class => MockResponse::fixture('ManualEntries/create-manual-entry-for-fetch-file'),
        AddFileToAccountingEntryLineRequest::class => MockResponse::fixture('ManualEntries/add-file-for-fetch-file'),
        FetchFilesOfAccountingEntryRequest::class => MockResponse::fixture('ManualEntries/fetch-files-for-fetch-file'),
        FetchFileOfAccountingEntryLineRequest::class => MockResponse::fixture('ManualEntries/fetch-file-of-an-accounting-entry-line'),
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

    $manualEntry = $connector->send(new CreateManualEntryRequest(
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

    if (! $manualEntry->successful()) {
        $this->markTestSkipped('Could not create a manual entry on this instance: '.$manualEntry->body());
    }

    $manualEntryDto = $manualEntry->dto();
    $entry = $manualEntryDto->entries->first();

    $addFile = $connector->send(new AddFileToAccountingEntryLineRequest(
        manual_entry_id: $manualEntryDto->id,
        entry_id: $entry->id,
        data: new AddFileDTO(
            name: 'image.png',
            absolute_file_path_or_stream: fopen(__DIR__.'/../../Fixtures/Files/image.png', 'r'),
            filename: 'image.png',
        )
    ));

    if (! $addFile->successful()) {
        $this->markTestSkipped('Could not attach a file to the accounting entry line on this instance: '.$addFile->body());
    }

    $files = $connector->send(new FetchFilesOfAccountingEntryRequest(
        manual_entry_id: $manualEntryDto->id,
        entry_id: $entry->id,
    ))->dto();

    $file = $files->first();

    if (! $file) {
        $this->markTestSkipped('No file is attached to the accounting entry line on this instance.');
    }

    $response = $connector->send(new FetchFileOfAccountingEntryLineRequest(
        manual_entry_id: $manualEntryDto->id,
        entry_id: $entry->id,
        file_id: $file->id,
    ));

    Saloon::assertSent(FetchFileOfAccountingEntryLineRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(FileDTO::class);
});
