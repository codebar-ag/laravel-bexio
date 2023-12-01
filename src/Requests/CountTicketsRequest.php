<?php

namespace CodebarAg\Zendesk\Requests;

use CodebarAg\Zendesk\Dto\Tickets\CountTicketsDTO;
use Exception;
use Saloon\Http\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class CountTicketsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/tickets/count.json';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CountTicketsDTO::fromResponse($response);
    }
}
