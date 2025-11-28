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
use CodebarAg\Bexio\Requests\Quotes\CreateAQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\EditAQuoteRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/edit-a-quote';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/create-a-quote.json');
        @unlink($fixturePath.'/edit-a-quote.json');
    }

    Saloon::fake([
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
        FetchAListOfBankAccountsRequest::class => MockResponse::fixture('BankAccounts/fetch-a-list-of-bank-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        FetchAListOfPaymentTypesRequest::class => MockResponse::fixture('PaymentTypes/fetch-a-list-of-payment-types'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        CreateAQuoteRequest::class => MockResponse::fixture('Quotes/edit-a-quote/create-a-quote'),
        EditAQuoteRequest::class => MockResponse::fixture('Quotes/edit-a-quote/edit-a-quote'),
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

    $updatedQuote = QuoteDTO::fromArray([
        'id' => $createdQuote->id,
        'title' => 'Updated Quote Title',
        'contact_id' => $createdQuote->contact_id,
        'user_id' => $createdQuote->user_id,
        'pr_project_id' => $createdQuote->pr_project_id,
        'logopaper_id' => $createdQuote->logopaper_id,
        'language_id' => $createdQuote->language_id,
        'bank_account_id' => $createdQuote->bank_account_id,
        'currency_id' => $createdQuote->currency_id,
        'payment_type_id' => $createdQuote->payment_type_id,
        'mwst_type' => $createdQuote->mwst_type,
        'mwst_is_net' => $createdQuote->mwst_is_net,
        'show_position_taxes' => $createdQuote->show_position_taxes,
        'is_valid_from' => $createdQuote->is_valid_from,
        'is_valid_until' => $createdQuote->is_valid_until,
        'api_reference' => $createdQuote->api_reference,
    ]);

    $response = $connector->send(new EditAQuoteRequest(
        quote_id: $createdQuote->id,
        quote: $updatedQuote
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(QuoteDTO::class);

    Saloon::assertSent(CreateAQuoteRequest::class);
    Saloon::assertSent(EditAQuoteRequest::class);
})->group('quotes');
