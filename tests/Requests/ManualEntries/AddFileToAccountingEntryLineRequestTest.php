<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ManualEntries\AddFileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ManualEntries\AddFileToAccountingEntryLineRequest;
use Illuminate\Support\Facades\File;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        //        AddFileToAccountingEntryLineRequest::class => MockResponse::fixture('ManualEntries/add-file-to-accounting-entry-line'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $response = $connector->send(new AddFileToAccountingEntryLineRequest(
        manual_entry_id: 1,
        entry_id: 1,
        data: new AddFileDTO(
            name: 'fileName',
            absolute_file_path_or_stream: fopen(__DIR__.'/../../Fixtures/Files/image.png', 'r'),
            filename: 'image.png',
        )
    ));

    ray($response->json());

    Saloon::assertSent(AddFileToAccountingEntryLineRequest::class);
})->skip('Not working yet. File is not uploaded.');
