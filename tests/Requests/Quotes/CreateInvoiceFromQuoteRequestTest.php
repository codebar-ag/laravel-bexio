<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\OfferPositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use CodebarAg\Bexio\Requests\BankAccounts\FetchAListOfBankAccountsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\Languages\FetchAListOfLanguagesRequest;
use CodebarAg\Bexio\Requests\PaymentTypes\FetchAListOfPaymentTypesRequest;
use CodebarAg\Bexio\Requests\Quotes\AcceptAQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\CreateAQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\CreateInvoiceFromQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\FetchAQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\IssueAQuoteRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/create-invoice-from-quote';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/create-a-quote.json');
        @unlink($fixturePath.'/issue-a-quote.json');
        @unlink($fixturePath.'/fetch-a-quote.json');
        @unlink($fixturePath.'/accept-a-quote.json');
        @unlink($fixturePath.'/create-invoice-from-quote.json');
    }

    Saloon::fake([
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
        FetchAListOfBankAccountsRequest::class => MockResponse::fixture('BankAccounts/fetch-a-list-of-bank-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        FetchAListOfPaymentTypesRequest::class => MockResponse::fixture('PaymentTypes/fetch-a-list-of-payment-types'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        CreateAQuoteRequest::class => MockResponse::fixture('Quotes/create-invoice-from-quote/create-a-quote'),
        IssueAQuoteRequest::class => MockResponse::fixture('Quotes/create-invoice-from-quote/issue-a-quote'),
        FetchAQuoteRequest::class => MockResponse::fixture('Quotes/create-invoice-from-quote/fetch-a-quote'),
        AcceptAQuoteRequest::class => MockResponse::fixture('Quotes/create-invoice-from-quote/accept-a-quote'),
        CreateInvoiceFromQuoteRequest::class => MockResponse::fixture('Quotes/create-invoice-from-quote/create-invoice-from-quote'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $contacts = $connector->send(new FetchAListOfContactsRequest);
    $user = $connector->send(new FetchAuthenticatedUserRequest);
    $languages = $connector->send(new FetchAListOfLanguagesRequest);
    $banks = $connector->send(new FetchAListOfBankAccountsRequest);
    $currencies = $connector->send(new FetchAListOfCurrenciesRequest);
    $paymentTypes = $connector->send(new FetchAListOfPaymentTypesRequest);
    $units = $connector->send(new FetchAListOfUnitsRequest);

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
            OfferPositionDTO::fromArray([
                'type' => 'KbPositionCustom',
                'amount' => 1,
                'unit_id' => $units->dto()->first()->id,
                'account_id' => 217,
                'tax_id' => 14,
                'text' => Str::uuid(),
                'unit_price' => 100,
                'discount_in_percent' => '0',
            ]),
        ],
    ]);

    $createResponse = $connector->send(new CreateAQuoteRequest(quote: $quote));
    $createdQuote = $createResponse->dto();

    $issueResponse = $connector->send(new IssueAQuoteRequest(quote_id: $createdQuote->id));
    expect($issueResponse->successful())->toBeTrue();

    // Fetch the quote again to check its status
    $fetchResponse = $connector->send(new FetchAQuoteRequest(quote_id: $createdQuote->id));
    expect($fetchResponse->successful())->toBeTrue();
    $currentQuote = $fetchResponse->dto();

    // Ensure the quote is in 'issued' status (ID 2) before accepting
    expect($currentQuote->kb_item_status_id)->toBe(2, 'Quote must be in status 2 (issued) before it can be accepted and converted to an invoice');

    // Accept the quote
    $acceptResponse = $connector->send(new AcceptAQuoteRequest(quote_id: $createdQuote->id));
    expect($acceptResponse->successful())->toBeTrue();

    // Now create an invoice from the created quote
    $response = $connector->send(new CreateInvoiceFromQuoteRequest(quote_id: $createdQuote->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(CreateAQuoteRequest::class);
    Saloon::assertSent(IssueAQuoteRequest::class);
    Saloon::assertSent(FetchAQuoteRequest::class);
    Saloon::assertSent(AcceptAQuoteRequest::class);
    Saloon::assertSent(CreateInvoiceFromQuoteRequest::class);
})->group('quotes');
