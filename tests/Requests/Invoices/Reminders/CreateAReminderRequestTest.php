<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\ReminderDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\CreateAReminderRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/create-a-reminder.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CreateAReminderRequest::class => MockResponse::fixture('Invoices/Reminders/create-a-reminder'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $reminder = ReminderDTO::fromArray([
        'id' => null,
        'title' => 'Reminder',
        'kb_invoice_id' => 52,
        'reminder_level_id' => 1,
        'is_sent' => false,
        'is_valid_from' => '2024-11-01',
        'is_valid_to' => '2024-11-15',
        'subject' => 'Payment reminder',
        'body' => 'Please settle the outstanding amount.',
        'salutation_id' => 1,
        'updated_at' => null,
    ]);

    $response = $connector->send(new CreateAReminderRequest(invoice_id: 52, reminder: $reminder));

    Saloon::assertSent(CreateAReminderRequest::class);

    expect($response->dto())->toBeInstanceOf(ReminderDTO::class);
})->group('invoices');
