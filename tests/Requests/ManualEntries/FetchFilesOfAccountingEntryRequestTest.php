<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ManualEntries\FetchAListOfManualEntriesRequest;
use CodebarAg\Bexio\Requests\ManualEntries\FetchFilesOfAccountingEntryRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfManualEntriesRequest::class => MockResponse::fixture('ManualEntries/fetch-a-list-of-manual-entries'),
        FetchFilesOfAccountingEntryRequest::class => MockResponse::fixture('ManualEntries/fetch-files-of-an-accounting-entry'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $manualEntry = $connector->send(new FetchAListOfManualEntriesRequest)->dto()->first();

    if (! $manualEntry) {
        $this->markTestSkipped('A manual entry with an accounting entry is required to fetch its files.');
    }

    $entry = $manualEntry->entries->first();

    if (! $entry || ! $entry->id) {
        $this->markTestSkipped('A manual entry with an accounting entry is required to fetch its files.');
    }

    $response = $connector->send(new FetchFilesOfAccountingEntryRequest(
        manual_entry_id: $manualEntry->id,
        entry_id: $entry->id,
    ));

    Saloon::assertSent(FetchFilesOfAccountingEntryRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class);
});
