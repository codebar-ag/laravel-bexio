<?php

namespace CodebarAg\Bexio\Requests\Currencies;

use CodebarAg\Bexio\Dto\Currencies\CreateCurrencyDTO;
use CodebarAg\Bexio\Dto\Currencies\CurrencyDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateCurrencyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateCurrencyDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/currencies';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateCurrencyDTO) {
            $body = CreateCurrencyDTO::fromArray($body);
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
