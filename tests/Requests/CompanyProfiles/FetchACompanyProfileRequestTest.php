<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\CompanyProfiles\FetchACompanyProfileRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchATaxRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchACompanyProfileRequest::class => MockResponse::fixture('CompanyProfiles/fetch-a-company-profile'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchACompanyProfileRequest(id: 1));

    $mockClient->assertSent(FetchACompanyProfileRequest::class);
});
