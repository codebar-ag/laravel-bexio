<?php

namespace CodebarAg\Bexio\Requests\Countries;

use CodebarAg\Bexio\Dto\Countries\CountryDTO;
use CodebarAg\Bexio\Dto\Countries\CreateEditCountryDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditACountryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $country_id,
        protected readonly array|CreateEditCountryDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/country/'.$this->country_id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditCountryDTO) {
            $body = CreateEditCountryDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): CountryDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CountryDTO::fromArray($response->json());
    }
}
