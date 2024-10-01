<?php

namespace CodebarAg\Bexio\Requests\Files;

use CodebarAg\Bexio\Dto\Files\FileDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasMultipartBody;

class CreateAFileRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/files';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): Collection
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
