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
        public readonly ?string $from = null,
        public readonly ?string $to = null,
        public readonly int|string|null $bill_id = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/payments';
    }

    public function defaultQuery(): array
    {
        $query = [];

        if ($this->from) {
            $query['from'] = $this->from;
        }

        if ($this->to) {
            $query['to'] = $this->to;
        }

        if ($this->bill_id) {
            $query['bill_id'] = $this->bill_id;
        }

        if ($this->limit) {
            $query['limit'] = $this->limit;
        }

        if ($this->offset) {
            $query['offset'] = $this->offset;
        }

        return $query;
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $payments = collect();

        foreach ($res as $payment) {
            $payments->push(PaymentDTO::fromArray($payment));
        }

        return $payments;
    }
}
