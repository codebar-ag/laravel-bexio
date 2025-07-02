<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\SetIssuedInvoiceToDraftRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SetIssuedInvoiceToDraftRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SetIssuedInvoiceToDraftRequest(invoice_id: 1));

    $mockClient->assertSent(SetIssuedInvoiceToDraftRequest::class);

    expect($response->json())->toHaveKey('success');
});
