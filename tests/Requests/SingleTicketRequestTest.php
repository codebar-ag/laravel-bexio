<?php

use CodebarAg\Zendesk\Enums\TicketPriority;
use CodebarAg\Zendesk\Requests\SingleTicketRequest;
use CodebarAg\Zendesk\ZendeskConnector;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get a single ticket', closure: function () {
    $mockClient = new MockClient([
        SingleTicketRequest::class => MockResponse::fixture('single-ticket-request'),
    ]);

    $connector = new ZendeskConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SingleTicketRequest(21));

    $mockClient->assertSent(SingleTicketRequest::class);

    expect($response->dto()->id)->toBe(21)
        ->and($response->dto()->subject)->toBe('My printer is on fire!')
        ->and($response->dto()->raw_subject)->toBe('My printer is on fire!')
        ->and($response->dto()->description)->toBe('The smoke is very colorful.')
        ->and($response->dto()->priority)->toBe(TicketPriority::URGENT)
        ->and($response->dto()->custom_fields[1]['id'])->toBe(10350920893084)
        ->and($response->dto()->custom_fields[1]['value'])->toBe('Check field works')
        ->and($response->dto()->custom_fields[2]['id'])->toBe(10350942541980)
        ->and($response->dto()->custom_fields[2]['value'])->toBe('Check field works number 2');
});
