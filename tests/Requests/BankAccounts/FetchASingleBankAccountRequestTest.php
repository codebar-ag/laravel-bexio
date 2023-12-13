<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\BankAccounts\FetchASingleBankAccountRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchASingleBankAccountRequest::class => MockResponse::fixture('BankAccounts/fetch-a-single-bank-account'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchASingleBankAccountRequest(id: 1));

    $mockClient->assertSent(FetchASingleBankAccountRequest::class);
});
