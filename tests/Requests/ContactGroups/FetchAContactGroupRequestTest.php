<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\ContactGroupDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\FetchAContactGroupRequest;
use CodebarAg\Bexio\Requests\ContactGroups\FetchAListOfContactGroupsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ContactGroups/fetch-a-contact-group.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/ContactGroups/fetch-a-list-of-contact-groups.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        FetchAContactGroupRequest::class => MockResponse::fixture('ContactGroups/fetch-a-contact-group'),
        FetchAListOfContactGroupsRequest::class => MockResponse::fixture('ContactGroups/fetch-a-list-of-contact-groups'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $existingGroup = $connector->send(new FetchAListOfContactGroupsRequest)->dto()->first();

    if (! $existingGroup) {
        $this->markTestSkipped('No contact group available to fetch');
    }

    $response = $connector->send(new FetchAContactGroupRequest(id: $existingGroup->id));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ContactGroupDTO::class);

    Saloon::assertSent(FetchAContactGroupRequest::class);
})->group('contact-groups');
