<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Dto\Invoices\ReminderDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Enums\Accounts\AccountTypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\BankAccounts\FetchAListOfBankAccountsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use CodebarAg\Bexio\Requests\Currencies\FetchAListOfCurrenciesRequest;
use CodebarAg\Bexio\Requests\Invoices\CreateAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Invoices\IssueAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Invoices\Reminders\CreateAReminderRequest;
use CodebarAg\Bexio\Requests\Invoices\Reminders\ShowPdfAReminderRequest;
use CodebarAg\Bexio\Requests\Languages\FetchAListOfLanguagesRequest;
use CodebarAg\Bexio\Requests\PaymentTypes\FetchAListOfPaymentTypesRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/show-pdf-a-reminder.json');
        @unlink(__DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/create-an-invoice-for-pdf-reminder.json');
        @unlink(__DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/issue-an-invoice-for-pdf-reminder.json');
        @unlink(__DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/create-a-reminder-for-pdf.json');
    }

    Saloon::fake([
        CreateAnInvoiceRequest::class => MockResponse::fixture('Invoices/Reminders/create-an-invoice-for-pdf-reminder'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
        FetchAListOfBankAccountsRequest::class => MockResponse::fixture('BankAccounts/fetch-a-list-of-bank-accounts'),
        FetchAListOfCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-a-list-of-currencies'),
        FetchAListOfPaymentTypesRequest::class => MockResponse::fixture('PaymentTypes/fetch-a-list-of-payment-types'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
        IssueAnInvoiceRequest::class => MockResponse::fixture('Invoices/Reminders/issue-an-invoice-for-pdf-reminder'),
        CreateAReminderRequest::class => MockResponse::fixture('Invoices/Reminders/create-a-reminder-for-pdf'),
        ShowPdfAReminderRequest::class => MockResponse::fixture('Invoices/Reminders/show-pdf-a-reminder'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    [$invoiceId, $reminderId] = createReminderForPdf($connector);

    if ($invoiceId === null || $reminderId === null) {
        $this->markTestSkipped('Unable to create an issued invoice with a reminder.');
    }

    $response = $connector->send(new ShowPdfAReminderRequest(invoice_id: $invoiceId, reminder_id: $reminderId));

    Saloon::assertSent(ShowPdfAReminderRequest::class);

    expect($response->dto())->toBeInstanceOf(PdfDTO::class);
})->group('invoices');

function createReminderForPdf(BexioConnector $connector): array
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

    $newInvoice = InvoiceDTO::fromArray([
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

    $invoice = $connector->send(new CreateAnInvoiceRequest(invoice: $newInvoice))->dto();

    if ($invoice === null) {
        return [null, null];
    }

    $connector->send(new IssueAnInvoiceRequest(invoice_id: $invoice->id));

    $reminder = ReminderDTO::fromArray([
        'id' => null,
        'kb_invoice_id' => $invoice->id,
        'title' => 'Reminder',
        'is_valid_from' => now()->format('Y-m-d'),
        'is_valid_to' => now()->addDays(14)->format('Y-m-d'),
        'reminder_period_in_days' => null,
        'reminder_level' => null,
        'show_positions' => true,
        'remaining_price' => null,
        'received_total' => null,
        'is_sent' => false,
        'header' => null,
        'footer' => null,
    ]);

    $created = $connector->send(new CreateAReminderRequest(invoice_id: $invoice->id, reminder: $reminder))->dto();

    return [$invoice->id, $created?->id];
}
