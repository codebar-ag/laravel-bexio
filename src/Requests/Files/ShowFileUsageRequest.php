<?php

namespace CodebarAg\Bexio\Requests\Files;

use CodebarAg\Bexio\Dto\Files\FileUsageDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ShowFileUsageRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/files/'.$this->id.'/usage';
    }

    public function createDtoFromResponse(Response $response): FileUsageDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return FileUsageDTO::fromResponse($response);
    }
}
