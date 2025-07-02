<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\CompanyProfiles\CompanyProfileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\CompanyProfiles\FetchACompanyProfileRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchACompanyProfileRequest::class => MockResponse::fixture('CompanyProfiles/fetch-a-company-profile'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchACompanyProfileRequest(id: 1));

    Saloon::assertSent(FetchACompanyProfileRequest::class);

    expect($response->dto())->toBeInstanceOf(CompanyProfileDTO::class);
});
