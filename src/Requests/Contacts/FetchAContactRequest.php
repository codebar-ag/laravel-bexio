<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAContactRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
        readonly bool $show_archived = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->id;
    }

    public function defaultQuery(): array
    {
        return [
            'show_archived' => $this->show_archived,
        ];
    }

    public function createDtoFromResponse(Response $response): ContactDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactDTO::fromResponse($response);
    }
}
