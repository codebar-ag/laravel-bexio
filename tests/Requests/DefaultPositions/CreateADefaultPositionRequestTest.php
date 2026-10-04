<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DefaultPositions\CreateEditDefaultPositionDTO;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\DefaultPositions\CreateADefaultPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('creates a default position for any supported document type', function (): void {
    Saloon::fake([
        CreateADefaultPositionRequest::class => MockResponse::make([
            'id' => 10,
            'amount' => '2.000000',
            'unit_id' => 3,
            'account_id' => 4,
            'tax_id' => 5,
            'text' => 'Lunch',
            'unit_price' => '12.500000',
            'discount_in_percent' => '0.000000',
            'type' => 'KbPositionCustom',
            'parent_id' => 99,
        ], 201),
    ]);

    $request = new CreateADefaultPositionRequest(
        kb_document_type: 'kb_order',
        document_id: 123,
        position: new CreateEditDefaultPositionDTO(
            amount: '2.000000',
            unit_id: 3,
            account_id: 4,
            tax_id: 5,
            text: 'Lunch',
            unit_price: '12.500000',
            discount_in_percent: '0.000000',
            parent_id: 99,
        ),
    );

    expect($request->resolveEndpoint())->toBe('/2.0/kb_order/123/kb_position_custom')
        ->and($request->defaultBody())->toBe([
            'amount' => '2.000000',
            'unit_id' => 3,
            'account_id' => 4,
            'tax_id' => 5,
            'text' => 'Lunch',
            'unit_price' => '12.500000',
            'discount_in_percent' => '0.000000',
            'parent_id' => 99,
        ]);

    $position = (new BexioConnector(new ConnectWithToken))->send($request)->dto();

    expect($position)->toBeInstanceOf(DefaultPositionDTO::class)
        ->and($position->type)->toBe('KbPositionCustom')
        ->and($position->parent_id)->toBe(99);
});
