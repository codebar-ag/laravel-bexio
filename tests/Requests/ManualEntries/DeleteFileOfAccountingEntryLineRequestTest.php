<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ManualEntries\DeleteFileOfAccountingEntryLineRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteFileOfAccountingEntryLineRequest::class => MockResponse::fixture('ManualEntries/delete-file-of-an-accounting-entry-line'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteFileOfAccountingEntryLineRequest(
        manual_entry_id: 2,
        entry_id: 2,
        file_id: 1,
    ));

    $mockClient->assertSent(DeleteFileOfAccountingEntryLineRequest::class);
});
