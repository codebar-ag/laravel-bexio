<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\SendAReminderRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SendAReminderRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SendAReminderRequest(
        invoice_id: 52,
        reminder_id: 1,
        payload: [
            'recipient_emails' => ['test@example.com'],
            'subject' => 'Reminder',
            'message' => 'Please settle the outstanding amount.',
            'mark_as_sent' => true,
        ],
    ));

    Saloon::assertSent(SendAReminderRequest::class);

    expect($response->json())->toHaveKey('success');
})->group('invoices');
