<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Reminders\SearchRemindersRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Reminders/search-reminders.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        SearchRemindersRequest::class => MockResponse::fixture('Invoices/Reminders/search-reminders'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchRemindersRequest(
        invoice_id: 52,
        searchField: 'subject',
        searchTerm: 'Payment reminder',
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(SearchRemindersRequest::class);
})->group('invoices');
