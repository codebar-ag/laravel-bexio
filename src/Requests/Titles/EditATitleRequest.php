<?php

namespace CodebarAg\Bexio\Requests\Titles;

use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Dto\Titles\TitleDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditATitleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $id,
        protected readonly array|CreateEditTitleDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/title/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditTitleDTO) {
            $body = CreateEditTitleDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): TitleDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return TitleDTO::fromArray($response->json());
    }
}
