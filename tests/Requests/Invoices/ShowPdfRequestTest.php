<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Requests\Invoices\ShowPdfRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        ShowPdfRequest::class => MockResponse::fixture('Invoices/show-pdf'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new ShowPdfRequest(53));

    $mockClient->assertSent(ShowPdfRequest::class);

    expect($response->dto())->toBeInstanceOf(PdfDTO::class);
});
