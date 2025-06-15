<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\PagebreakPositions\CreateEditPagebreakPositionDTO;
use CodebarAg\Bexio\Dto\PagebreakPositions\PagebreakPositionDTO;
use CodebarAg\Bexio\Requests\PagebreakPositions\CreateAPagebreakPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateAPagebreakPositionRequest::class => MockResponse::fixture('PagebreakPositions/create-a-pagebreak-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateAPagebreakPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditPagebreakPositionDTO(
            pagebreak: true,
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateAPagebreakPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(PagebreakPositionDTO::class);
});
