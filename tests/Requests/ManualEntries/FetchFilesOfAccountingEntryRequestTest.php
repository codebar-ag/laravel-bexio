<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ManualEntries\FetchFilesOfAccountingEntryRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchFilesOfAccountingEntryRequest::class => MockResponse::fixture('ManualEntries/fetch-files-of-an-accounting-entry'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchFilesOfAccountingEntryRequest(
        manual_entry_id: 1,
        entry_id: 1,
    ));

    Saloon::assertSent(FetchFilesOfAccountingEntryRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
