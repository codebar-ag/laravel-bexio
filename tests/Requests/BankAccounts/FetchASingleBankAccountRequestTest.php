<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\BankAccounts\FetchASingleBankAccountRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchASingleBankAccountRequest::class => MockResponse::fixture('BankAccounts/fetch-a-single-bank-account'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchASingleBankAccountRequest(id: 1));

    Saloon::assertSent(FetchASingleBankAccountRequest::class);
});
