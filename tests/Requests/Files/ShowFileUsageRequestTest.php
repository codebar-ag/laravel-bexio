<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use CodebarAg\Bexio\Requests\Files\ShowFileUsageRequest;
use CodebarAg\Bexio\Requests\Salutations\FetchASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
//        ShowFileUsageRequest::class => MockResponse::fixture('Files/show-file-usage'),
    ]);

    $connector = new BexioConnector;
//    $connector->withMockClient($mockClient);

    $response = $connector->send(new ShowFileUsageRequest(
        id: 1,
    ));

    ray($response->dto());

    $mockClient->assertSent(ShowFileUsageRequest::class);
})->skip('Not returning data.');
