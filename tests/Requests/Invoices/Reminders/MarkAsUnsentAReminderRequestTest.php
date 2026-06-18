<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\MarkAsUnsentAReminderRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        MarkAsUnsentAReminderRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new MarkAsUnsentAReminderRequest(invoice_id: 52, reminder_id: 1));

    Saloon::assertSent(MarkAsUnsentAReminderRequest::class);

    expect($response->json())->toHaveKey('success');
})->group('invoices');
