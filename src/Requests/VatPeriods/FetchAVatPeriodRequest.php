<?php

namespace CodebarAg\Bexio\Requests\VatPeriods;

use CodebarAg\Bexio\Dto\VatPeriods\VatPeriodDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAVatPeriodRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/vat_periods/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): VatPeriodDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return VatPeriodDTO::fromResponse($response);
    }
}
