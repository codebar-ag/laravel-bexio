<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Requests\Titles\CreateATitleRequest;
use CodebarAg\Bexio\Requests\Titles\DeleteATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Titles/delete-a-title.json');
        @unlink(__DIR__.'/../../Fixtures/Saloon/Titles/create-a-title-for-delete.json');
    }

    Saloon::fake([
        CreateATitleRequest::class => MockResponse::fixture('Titles/create-a-title-for-delete'),
        DeleteATitleRequest::class => MockResponse::fixture('Titles/delete-a-title'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $created = $connector->send(new CreateATitleRequest(
        data: new CreateEditTitleDTO(name: 'Test title '.Str::random(6)),
    ))->dto();

    $response = $connector->send(new DeleteATitleRequest(id: $created->id));

    Saloon::assertSent(DeleteATitleRequest::class);

    expect($response->successful())->toBeTrue();
});
