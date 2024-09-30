<?php

namespace CodebarAg\Bexio\Requests\Salutations;

use CodebarAg\Bexio\Dto\Salutations\SalutationDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchASalutationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/salutation/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): SalutationDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return SalutationDTO::fromResponse($response);
    }
}
