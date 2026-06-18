<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Dto\Salutations\SalutationDTO;
use CodebarAg\Bexio\Requests\Salutations\CreateASalutationRequest;
use CodebarAg\Bexio\Requests\Salutations\EditASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Salutations/edit-a-salutation.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Salutations/create-a-salutation-for-edit.json');
    }

    Saloon::fake([
        CreateASalutationRequest::class => MockResponse::fixture('Salutations/create-a-salutation-for-edit'),
        EditASalutationRequest::class => MockResponse::fixture('Salutations/edit-a-salutation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $created = $connector->send(new CreateASalutationRequest(
        data: new CreateEditSalutationDTO(name: 'Test salutation '.Str::random(6)),
    ))->dto();

    $response = $connector->send(new EditASalutationRequest(
        id: $created->id,
        data: new CreateEditSalutationDTO(name: 'Test name edited '.Str::random(6)),
    ));

    Saloon::assertSent(EditASalutationRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(SalutationDTO::class);
});
