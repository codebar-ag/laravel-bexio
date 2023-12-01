<?php

namespace CodebarAg\Zendesk\Requests;

use CodebarAg\Zendesk\Dto\Tickets\SingleTicketDTO;
use Exception;
use Saloon\Http\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class SingleTicketRequest extends Request
{
    protected Method $method = Method::GET;

    protected int $ticketId;

    public function __construct(int $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function resolveEndpoint(): string
    {
        return '/tickets/'.$this->ticketId.'.json';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return SingleTicketDTO::fromResponse($response);
    }
}
