<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\ShowPdfRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        ShowPdfRequest::class => MockResponse::fixture('Invoices/show-pdf'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new ShowPdfRequest(53));

    Saloon::assertSent(ShowPdfRequest::class);

    expect($response->dto())->toBeInstanceOf(PdfDTO::class);
});
