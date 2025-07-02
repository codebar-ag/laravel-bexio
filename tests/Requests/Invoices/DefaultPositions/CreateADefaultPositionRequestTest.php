<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\Invoices\DefaultPositions\CreateADefaultPositionRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateADefaultPositionRequest::class => MockResponse::fixture('Invoices/DefaultPositions/create-a-default-position'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $units = $connector->send(new FetchAListOfUnitsRequest);
    $accounts = $connector->send(new FetchAListOfAccountsRequest);
    $taxes = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'));

    $position = InvoicePositionDTO::fromArray([
        'type' => 'KbPositionCustom',
        'amount' => 1,
        'unit_id' => $units->dto()->first()->id,
        'account_id' => $accounts->dto()->filter(fn ($account) => $account->account_type === 1)->first()->id,
        'tax_id' => $taxes->dto()->first()->id,
        'text' => Str::uuid(),
        'unit_price' => 100,
        'discount_in_percent' => '0',
    ]);

    $response = $connector->send(new CreateADefaultPositionRequest(
        kb_document_type: 'kb_invoice',
        invoice_id: 53,
        position: $position,
    ));

    $mockClient->assertSent(CreateADefaultPositionRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoicePositionDTO::class);
});
