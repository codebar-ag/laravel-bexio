<?php

namespace CodebarAg\Bexio\Requests\ContactRelations;

use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateContactRelationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateEditContactRelationDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact_relation';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditContactRelationDTO) {
            $body = CreateEditContactRelationDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): ContactDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactDTO::fromArray($response->json());
    }
}
