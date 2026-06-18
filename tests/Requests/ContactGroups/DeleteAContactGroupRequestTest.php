<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\CreateContactGroupRequest;
use CodebarAg\Bexio\Requests\ContactGroups\DeleteAContactGroupRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ContactGroups/delete-a-contact-group.json';
    $createFixturePath = __DIR__.'/../../Fixtures/Saloon/ContactGroups/delete-a-contact-group-create.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($createFixturePath);
    }

    Saloon::fake([
        DeleteAContactGroupRequest::class => MockResponse::fixture('ContactGroups/delete-a-contact-group'),
        CreateContactGroupRequest::class => MockResponse::fixture('ContactGroups/delete-a-contact-group-create'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $group = $connector->send(new CreateContactGroupRequest(
        new CreateEditContactGroupDTO(
            name: 'Delete '.Str::uuid(),
        )
    ))->dto();

    $response = $connector->send(new DeleteAContactGroupRequest(id: $group->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteAContactGroupRequest::class);
})->group('contact-groups');
