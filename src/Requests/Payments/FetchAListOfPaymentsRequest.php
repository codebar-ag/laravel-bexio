<?php

namespace CodebarAg\Bexio\Requests\Payments;

use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfPaymentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly ?string $filterBy = null,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/4.0/banking/payments';
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'filter-by' => $this->filterBy,
            'page' => $this->page,
            'per-page' => $this->perPage,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return collect($response->json('results', []))
            ->map(fn (array $payment) => PaymentDTO::fromArray($payment));
    }
}
