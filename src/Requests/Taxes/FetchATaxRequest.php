<?php

namespace CodebarAg\Bexio\Requests\Taxes;

use CodebarAg\Bexio\Dto\Taxes\TaxDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchATaxRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/taxes/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): TaxDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return TaxDTO::fromResponse($response);
    }
}
