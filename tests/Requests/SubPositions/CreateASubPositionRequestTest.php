<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\SubPositions\CreateEditSubPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use CodebarAg\Bexio\Requests\SubPositions\CreateASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('creates a sub position for any supported document type', function (): void {
    Saloon::fake([
        CreateASubPositionRequest::class => MockResponse::make([
            'id' => 10,
            'text' => 'Meals',
            'show_pos_nr' => true,
            'show_pos_prices' => true,
            'total_sum' => '25.000000',
            'type' => 'KbPositionSubposition',
            'parent_id' => null,
        ], 201),
    ]);

    $request = new CreateASubPositionRequest(
        kb_document_type: 'kb_offer',
        document_id: 123,
        position: new CreateEditSubPositionDTO(
            text: 'Meals',
            show_pos_nr: true,
        ),
    );

    expect($request->resolveEndpoint())->toBe('/2.0/kb_offer/123/kb_position_subposition')
        ->and($request->defaultBody())->toBe([
            'text' => 'Meals',
            'show_pos_nr' => true,
        ]);

    $position = (new BexioConnector(new ConnectWithToken))->send($request)->dto();

    expect($position)->toBeInstanceOf(SubPositionDTO::class)
        ->and($position->type)->toBe('KbPositionSubposition')
        ->and($position->show_pos_prices)->toBeTrue()
        ->and($position->total_sum)->toBe('25.000000');
});
