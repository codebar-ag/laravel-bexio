<?php

namespace CodebarAg\Bexio\Requests\Currencies;

use CodebarAg\Bexio\Dto\Currencies\ExchangeCurrencyDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchExchangeRatesForCurrenciesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/currencies/'.$this->id.'/exchange_rates';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $exchangeRates = collect();

        foreach ($res as $exchangeRate) {
            $exchangeRates->push(ExchangeCurrencyDTO::fromArray($exchangeRate));
        }

        return $exchangeRates;
    }
}
