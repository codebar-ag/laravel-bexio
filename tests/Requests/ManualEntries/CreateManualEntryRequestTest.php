<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ManualEntries\CreateEntryDTO;
use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Enums\ManualEntries\TypeEnum;
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
            type: TypeEnum::MANUAL_SINGLE_ENTRY(),
            date: '2024-01-05',
            entries: collect([
                new CreateEntryDTO(
                    tax_id: 10,
                    tax_account_id: 89,
                    description: 'Testas asd',
                    amount: 100,
                    currency_id: 1,
                    currency_factor: 1,
                    debit_account_id: 89,
                    credit_account_id: 90,
                ),
            ]),
            reference_nr: '123123',
        )
    ));

    $mockClient->assertSent(CreateManualEntryRequest::class);
});
