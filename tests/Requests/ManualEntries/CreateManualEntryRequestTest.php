<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ManualEntries\CreateEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Enums\ManualEntryTypeEnum;
use CodebarAg\Bexio\Requests\ManualEntries\CreateManualEntryRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateManualEntryRequest::class => MockResponse::fixture('ManualEntries/create-manual-entry'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateManualEntryRequest(
        new CreateManualEntryDTO(
            type: ManualEntryTypeEnum::MANUAL_SINGLE_ENTRY(),
            date: '2023-12-12',
            reference_nr: 'qsdgqsrbeqrasdqergwerg2',
            entries: collect([
                new CreateEntryDTO(
                    debit_account_id: 89,
                    credit_account_id: 90,
                    tax_id: 10,
                    tax_account_id: 89,
                    description: 'Testas asd',
                    amount: 100,
                    currency_id: 1,
                    currency_factor: 1,
                ),
            ]),
        )
    ));

    $mockClient->assertSent(CreateManualEntryRequest::class);
});
