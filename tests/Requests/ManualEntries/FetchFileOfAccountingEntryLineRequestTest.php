<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ManualEntries\FetchFileOfAccountingEntryLineRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchFileOfAccountingEntryLineRequest::class => MockResponse::fixture('ManualEntries/fetch-file-of-an-accounting-entry-line'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchFileOfAccountingEntryLineRequest(
        manual_entry_id: 2,
        entry_id: 2,
        file_id: 1,
    ));

    Saloon::assertSent(FetchFileOfAccountingEntryLineRequest::class);
});
