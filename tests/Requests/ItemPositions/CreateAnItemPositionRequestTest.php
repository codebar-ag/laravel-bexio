<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\CreateEditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Enums\Accounts\AccountTypeEnum;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use CodebarAg\Bexio\Requests\ItemPositions\CreateAnItemPositionRequest;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use CodebarAg\Bexio\Requests\Taxes\FetchAListOfTaxesRequest;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/create-an-item-position.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CreateAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/create-an-item-position'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/fetch-a-list-of-quotes'),
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
        FetchAListOfTaxesRequest::class => MockResponse::fixture('Taxes/fetch-a-list-of-taxes-scoped_active-types_sales_tax'),
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quote = $connector->send(new FetchAListOfQuotesRequest)->dto()->first();
    $item = $connector->send(new FetchAListOfItemsRequest)->dto()->first();
    $tax = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'))->dto()->first();
    $account = $connector->send(new FetchAListOfAccountsRequest)->dto()
        ->filter(fn ($account) => $account->account_type === AccountTypeEnum::EARNINGS()->value)
        ->first();
    $unit = $connector->send(new FetchAListOfUnitsRequest)->dto()->first();

    if (! $quote || ! $item || ! $tax || ! $account || ! $unit) {
        $this->markTestSkipped('A quote (kb_offer), an article, a tax, an earnings account and a unit are required to create an item position');
    }

    $response = $connector->send(new CreateAnItemPositionRequest(
        kb_document_type: 'kb_offer',
        document_id: $quote->id,
        itemPosition: new CreateEditItemPositionDTO(
            type: 'KbPositionArticle',
            amount: '1',
            unit_id: $unit->id,
            account_id: $account->id,
            tax_id: $tax->id,
            text: (string) Str::uuid(),
            unit_price: '100',
            discount_in_percent: '0',
            article_id: $item->id,
        ),
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);

    Saloon::assertSent(CreateAnItemPositionRequest::class);
})->group('item-positions');
