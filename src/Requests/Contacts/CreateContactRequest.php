<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateEditContactDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditContactDTO) {
            $body = CreateEditContactDTO::fromArray($body);
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
