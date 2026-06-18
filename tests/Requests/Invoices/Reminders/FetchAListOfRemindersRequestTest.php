<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\FetchAListOfRemindersRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/fetch-a-list-of-reminders.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfRemindersRequest::class => MockResponse::fixture('Invoices/Reminders/fetch-a-list-of-reminders'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfRemindersRequest(invoice_id: 52));

    Saloon::assertSent(FetchAListOfRemindersRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class);
})->group('invoices');
