<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Reports\JournalRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        JournalRequest::class => MockResponse::fixture('Reports/journal.json'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new JournalRequest(
        from: '1970-01-01',
        to: '2023-12-21',
        account_id: '89',
    ));

    Saloon::assertSent(JournalRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
