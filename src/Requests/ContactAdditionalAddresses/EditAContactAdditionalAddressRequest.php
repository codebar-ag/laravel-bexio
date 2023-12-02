<?php

namespace CodebarAg\Bexio\Requests\ContactAdditionalAddresses;

use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\ContactAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\CreateEditContactAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditAContactAdditionalAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $contactId,
        readonly int $id,
        readonly protected array|CreateEditContactAdditionalAddressDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact/'.$this->contactId.'/additional_address/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditContactAdditionalAddressDTO) {
            $body = CreateEditContactAdditionalAddressDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactAdditionalAddressDTO::fromArray($response->json());
    }
}
