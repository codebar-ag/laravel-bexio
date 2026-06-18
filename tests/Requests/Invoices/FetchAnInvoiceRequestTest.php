<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Enums\Accounts\AccountTypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\BankAccounts\FetchAListOfBankAccountsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\Invoices\CreateAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Invoices\FetchAListOfInvoicesRequest;
use CodebarAg\Bexio\Requests\Invoices\FetchAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Languages\FetchAListOfLanguagesRequest;
use CodebarAg\Bexio\Requests\PaymentTypes\FetchAListOfPaymentTypesRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Invoices/fetch-an-invoice.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfInvoicesRequest::class => MockResponse::fixture('Invoices/fetch-a-list-of-invoices'),
        CreateAnInvoiceRequest::class => MockResponse::fixture('Invoices/create-an-invoice'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
        FetchAListOfBankAccountsRequest::class => MockResponse::fixture('BankAccounts/fetch-a-list-of-bank-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        FetchAListOfPaymentTypesRequest::class => MockResponse::fixture('PaymentTypes/fetch-a-list-of-payment-types'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
        FetchAnInvoiceRequest::class => MockResponse::fixture('Invoices/fetch-an-invoice'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $invoices = $connector->send(new FetchAListOfInvoicesRequest)->dto();

    $invoice = $invoices->first();

    if ($invoice === null) {
        $invoice = createInvoiceForFetch($connector);
    }

    if ($invoice === null) {
        $this->markTestSkipped('No invoice available and unable to create one.');
    }

    $response = $connector->send(new FetchAnInvoiceRequest(invoice_id: $invoice->id));

    Saloon::assertSent(FetchAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class);
})->group('invoices');

function createInvoiceForFetch(BexioConnector $connector): ?InvoiceDTO
{
    $contacts = $connector->send(new FetchAListOfContactsRequest);
    $user = $connector->send(new FetchAuthenticatedUserRequest);
    $languages = $connector->send(new FetchAListOfLanguagesRequest);
    $banks = $connector->send(new FetchAListOfBankAccountsRequest);
    $currencies = $connector->send(new FetchAListOfCurrenciesRequest);
    $paymentTypes = $connector->send(new FetchAListOfPaymentTypesRequest);
    $units = $connector->send(new FetchAListOfUnitsRequest);
    $accounts = $connector->send(new FetchAListOfAccountsRequest);
    $taxes = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'));

    $invoice = InvoiceDTO::fromArray([
        'title' => 'Test',
        'contact_id' => $contacts->dto()->first()->id,
        'user_id' => $user->dto()->id,
        'pr_project_id' => null,
        'language_id' => $languages->dto()->first()->id,
        'bank_account_id' => $banks->dto()->first()->id,
        'currency_id' => $currencies->dto()->first()->id,
        'payment_type_id' => $paymentTypes->dto()->first()->id,
        'mwst_type' => 1,
        'mwst_is_net' => true,
        'show_position_taxes' => true,
        'is_valid_from' => now()->format('Y-m-d h:m:s'),
        'is_valid_to' => now()->addDays(5)->format('Y-m-d h:m:s'),
        'api_reference' => Str::uuid(),
        'positions' => [
            InvoicePositionDTO::fromArray([
                'type' => 'KbPositionCustom',
                'amount' => 1,
                'unit_id' => $units->dto()->first()->id,
                'account_id' => $accounts->dto()->filter(fn ($account) => $account->account_type === AccountTypeEnum::EARNINGS()->value)->first()->id,
                'tax_id' => $taxes->dto()->first()->id,
                'text' => Str::uuid(),
                'unit_price' => 100,
                'discount_in_percent' => '0',
            ]),
        ],
    ]);

    return $connector->send(new CreateAnInvoiceRequest(invoice: $invoice))->dto();
}
