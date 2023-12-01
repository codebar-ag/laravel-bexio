<?php

use CodebarAg\Zendesk\Dto\Tickets\Comments\CommentDTO;
use CodebarAg\Zendesk\Enums\TicketPriority;
use CodebarAg\Zendesk\Requests\CreateSingleTicketRequest;
use CodebarAg\Zendesk\ZendeskConnector;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get create a single ticket', closure: function () {
    $mockClient = new MockClient([
        CreateSingleTicketRequest::class => MockResponse::fixture('create-single-ticket-request'),
    ]);

    $connector = new ZendeskConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateSingleTicketRequest([
        'comment' => CommentDTO::fromArray([
            'body' => 'The smoke is very colorful.',
        ]),
        'priority' => TicketPriority::URGENT,
        'subject' => 'My printer is on fire!',
        'custom_fields' => [
            [
                'id' => 10350920893084,
                'value' => 'Check field works',
            ],
            [
                'id' => 10350942541980,
                'value' => 'Check field works number 2',
            ],
        ],
    ]));

    $mockClient->assertSent(CreateSingleTicketRequest::class);

    expect($response->dto()->subject)->toBe('My printer is on fire!')
        ->and($response->dto()->raw_subject)->toBe('My printer is on fire!')
        ->and($response->dto()->description)->toBe('The smoke is very colorful.')
        ->and($response->dto()->priority)->toBe(TicketPriority::URGENT)
        ->and($response->dto()->custom_fields[1]['id'])->toBe(10350920893084)
        ->and($response->dto()->custom_fields[1]['value'])->toBe('Check field works')
        ->and($response->dto()->custom_fields[2]['id'])->toBe(10350942541980)
        ->and($response->dto()->custom_fields[2]['value'])->toBe('Check field works number 2');
});
