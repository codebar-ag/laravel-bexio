<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\BusinessActivities\FetchAListOfBusinessActivitiesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', function () {
    Saloon::fake([
        FetchAListOfBusinessActivitiesRequest::class => MockResponse::fixture('BusinessActivities/fetch-a-list-of-business-activities'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfBusinessActivitiesRequest);

    Saloon::assertSent(FetchAListOfBusinessActivitiesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(5);
});
