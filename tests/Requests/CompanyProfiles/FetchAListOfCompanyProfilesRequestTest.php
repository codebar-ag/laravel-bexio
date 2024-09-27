<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\CompanyProfiles\FetchAListOfCompanyProfilesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfCompanyProfilesRequest::class => MockResponse::fixture('CompanyProfiles/fetch-a-list-of-company-profiles'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfCompanyProfilesRequest);

    $mockClient->assertSent(FetchAListOfCompanyProfilesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
