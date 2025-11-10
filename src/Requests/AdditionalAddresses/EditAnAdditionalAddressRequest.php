<?php

namespace CodebarAg\Bexio\Requests\AdditionalAddresses;

use CodebarAg\Bexio\Dto\AdditionalAddresses\AdditionalAddressDTO;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditAnAdditionalAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $contactId,
        public readonly int $id,
        protected readonly array|CreateEditAdditionalAddressDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->contactId.'/additional_address/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditAdditionalAddressDTO) {
            $body = CreateEditAdditionalAddressDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): AdditionalAddressDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return AdditionalAddressDTO::fromArray($response->json());
    }
}
