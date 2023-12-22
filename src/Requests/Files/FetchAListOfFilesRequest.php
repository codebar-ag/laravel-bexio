<?php

namespace CodebarAg\Bexio\Requests\Files;

use CodebarAg\Bexio\Dto\Files\FileDTO;
use CodebarAg\Bexio\Dto\Salutations\SalutationDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfFilesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/files';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $files = collect();

        foreach ($res as $file) {
            $files->push(FileDTO::fromArray($file));
        }

        return $files;
    }
}
