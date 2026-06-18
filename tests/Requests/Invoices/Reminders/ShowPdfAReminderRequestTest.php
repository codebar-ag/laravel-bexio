<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\ShowPdfAReminderRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/show-pdf-a-reminder.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        ShowPdfAReminderRequest::class => MockResponse::fixture('Invoices/Reminders/show-pdf-a-reminder'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new ShowPdfAReminderRequest(invoice_id: 52, reminder_id: 1));

    Saloon::assertSent(ShowPdfAReminderRequest::class);

    expect($response->dto())->toBeInstanceOf(PdfDTO::class);
})->group('invoices');
