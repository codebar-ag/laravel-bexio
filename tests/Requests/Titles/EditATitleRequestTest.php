<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Dto\Titles\TitleDTO;
use CodebarAg\Bexio\Requests\Titles\CreateATitleRequest;
use CodebarAg\Bexio\Requests\Titles\EditATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Titles/edit-a-title.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Titles/create-a-title-for-edit.json');
    }

    Saloon::fake([
        CreateATitleRequest::class => MockResponse::fixture('Titles/create-a-title-for-edit'),
        EditATitleRequest::class => MockResponse::fixture('Titles/edit-a-title'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $created = $connector->send(new CreateATitleRequest(
        data: new CreateEditTitleDTO(name: 'Test title '.Str::random(6)),
    ))->dto();

    $response = $connector->send(new EditATitleRequest(
        id: $created->id,
        data: new CreateEditTitleDTO(name: 'Test name edited '.Str::random(6)),
    ));

    Saloon::assertSent(EditATitleRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(TitleDTO::class);
});
