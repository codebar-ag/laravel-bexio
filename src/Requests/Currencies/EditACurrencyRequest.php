<?php

namespace CodebarAg\Bexio\Requests\Currencies;

use CodebarAg\Bexio\Dto\Currencies\CurrencyDTO;
use CodebarAg\Bexio\Dto\Currencies\EditCurrencyDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditACurrencyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        readonly int $id,
        readonly protected array|EditCurrencyDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/currencies/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof EditCurrencyDTO) {
            $body = EditCurrencyDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CurrencyDTO::fromArray($response->json());
    }
}
