<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use CodebarAg\Bexio\Enums\Accounts\AccountTypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\BankAccounts\FetchAListOfBankAccountsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\Languages\FetchAListOfLanguagesRequest;
use CodebarAg\Bexio\Requests\PaymentTypes\FetchAListOfPaymentTypesRequest;
use CodebarAg\Bexio\Requests\Quotes\CreateAQuoteRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/create-a-quote.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    $successfulQuoteResponse = [
        'id' => 53,
        'document_nr' => 'AN-00053',
        'title' => 'Test Quote',
        'contact_id' => 1,
        'contact_sub_id' => null,
        'user_id' => 1,
        'project_id' => null,
        'logopaper_id' => 1,
        'language_id' => 1,
        'bank_account_id' => 1,
        'currency_id' => 1,
        'payment_type_id' => 1,
        'header' => 'Guten Tag bexio AG<br/><br/>Danke für Ihr Vertrauen. Ihr Angebot setzt sich wie folgt zusammen:',
        'footer' => 'Sie haben Fragen? Melden Sie sich bei uns.<br/><br/>Freundliche Grüsse<br/>Moritz Bleibtreu',
        'total_gross' => '100',
        'total_net' => '100',
        'total_taxes' => '0.0000',
        'total' => '100',
        'total_rounding_difference' => 0,
        'mwst_type' => 1,
        'mwst_is_net' => true,
        'show_position_taxes' => true,
        'is_valid_from' => '2024-10-28',
        'is_valid_until' => '2024-11-02',
        'contact_address' => "bexio AG\nAlte Jonastrasse 24\n8640 Rapperswil\nSchweiz",
        'kb_item_status_id' => 7,
        'reference' => '',
        'api_reference' => '1a167f92-3fbd-4dab-9eb5-f3d58f4d6193',
        'viewed_by_client_at' => null,
        'updated_at' => '2024-10-28 20:36:21',
        'esr_id' => 3,
        'qr_invoice_id' => 3,
        'template_slug' => '671cf7fbbc8c28f1cb036f10',
        'taxs' => [],
        'positions' => [
            [
                'id' => 1,
                'type' => 'KbPositionText',
                'text' => '845a03b4-12ec-42b5-876c-273e1d26656f',
                'show_pos_nr' => true,
                'pos' => '1',
                'internal_pos' => 1,
                'parent_id' => null,
                'is_optional' => false,
            ],
            [
                'id' => 64,
                'type' => 'KbPositionCustom',
                'amount' => '1',
                'unit_id' => 1,
                'account_id' => 95,
                'unit_name' => 'Stk',
                'tax_id' => 28,
                'tax_value' => '8.10',
                'text' => 'bcc08db8-f711-4281-9467-028be21e75b2',
                'unit_price' => '100',
                'discount_in_percent' => '0',
                'position_total' => '100',
                'pos' => '2',
                'internal_pos' => 2,
                'parent_id' => null,
                'is_optional' => false,
            ],
        ],
        'network_link' => '',
    ];

    Saloon::fake([
        CreateAQuoteRequest::class => MockResponse::make(body: $successfulQuoteResponse, status: 201),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
        FetchAListOfBankAccountsRequest::class => MockResponse::fixture('BankAccounts/fetch-a-list-of-bank-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        FetchAListOfPaymentTypesRequest::class => MockResponse::fixture('PaymentTypes/fetch-a-list-of-payment-types'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $contacts = $connector->send(new FetchAListOfContactsRequest);
    $user = $connector->send(new FetchAuthenticatedUserRequest);
    $languages = $connector->send(new FetchAListOfLanguagesRequest);
    $banks = $connector->send(new FetchAListOfBankAccountsRequest);
    $currencies = $connector->send(new FetchAListOfCurrenciesRequest);
    $paymentTypes = $connector->send(new FetchAListOfPaymentTypesRequest);
    $units = $connector->send(new FetchAListOfUnitsRequest);
    $accounts = $connector->send(new FetchAListOfAccountsRequest);
    $taxes = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'));

    $quote = QuoteDTO::fromArray([
        'title' => 'Test Quote',
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
        'is_valid_from' => now()->format('Y-m-d'),
        'is_valid_until' => now()->addDays(5)->format('Y-m-d'),
        'api_reference' => Str::uuid(),
        'positions' => [
            InvoicePositionDTO::fromArray([
                'type' => 'KbPositionText',
                'show_pos_nr' => true,
                'text' => Str::uuid(),
            ]),
            InvoicePositionDTO::fromArray([
                'type' => 'KbPositionCustom',
                'amount' => 1,
                'unit_id' => $units->dto()->first()->id,
                'account_id' => $accounts->dto()->filter(fn ($account) => $account->account_type_enum === AccountTypeEnum::ACTIVE_ACCOUNTS())->first()->id,
                'tax_id' => $taxes->dto()->first()->id,
                'text' => Str::uuid(),
                'unit_price' => 100,
                'discount_in_percent' => '0',
            ]),
        ],
    ]);

    $response = $connector->send(new CreateAQuoteRequest(quote: $quote));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(QuoteDTO::class);

    Saloon::assertSent(CreateAQuoteRequest::class);
})->group('quotes');
