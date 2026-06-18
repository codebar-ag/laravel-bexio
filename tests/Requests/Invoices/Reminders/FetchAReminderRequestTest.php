<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\ReminderDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\FetchAReminderRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/fetch-a-reminder.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAReminderRequest::class => MockResponse::fixture('Invoices/Reminders/fetch-a-reminder'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAReminderRequest(invoice_id: 52, reminder_id: 1));

    Saloon::assertSent(FetchAReminderRequest::class);

    expect($response->dto())->toBeInstanceOf(ReminderDTO::class);
})->group('invoices');
