<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\PagebreakPositions\CreateEditPagebreakPositionDTO;
use CodebarAg\Bexio\Dto\PagebreakPositions\PagebreakPositionDTO;
use CodebarAg\Bexio\Requests\PagebreakPositions\EditAPagebreakPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a pagebreak position', function () {
    $mockClient = new MockClient([
        EditAPagebreakPositionRequest::class => MockResponse::fixture('PagebreakPositions/edit-a-pagebreak-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditAPagebreakPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new CreateEditPagebreakPositionDTO(
            pagebreak: false,
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditAPagebreakPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(PagebreakPositionDTO::class);
});
