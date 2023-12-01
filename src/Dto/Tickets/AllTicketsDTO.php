<?php

namespace CodebarAg\Zendesk\Dto\Tickets;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class AllTicketsDTO extends Data
{
    public function __construct(
        public array $tickets,
        public int $count,
        public ?string $next_page_url,
        public ?string $previous_page_url,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to get all tickets', $response->status());
        }

        $data = $response->json();

        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            tickets: collect(Arr::get($data, 'tickets'))->map(function (array $ticket) {
                return SingleTicketDTO::fromArray($ticket);
            })->toArray(),
            count: Arr::get($data, 'count'),
            next_page_url: Arr::get($data, 'next_page'),
            previous_page_url: Arr::get($data, 'previous_page'),
        );
    }
}
