<?php

namespace CodebarAg\Bexio\Requests\Reports;

use CodebarAg\Bexio\Dto\Reports\JournalDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class JournalRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly string $from,
        readonly string $to,
        readonly string $account_id,
        readonly int $limit = 2000,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/journal';
    }

    public function defaultQuery(): array
    {
        $query = [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];

        return $query;
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $taxes = collect();

        foreach ($res as $currency) {
            $taxes->push(JournalDTO::fromArray($currency));
        }

        return $taxes;
    }
}
