<?php

namespace CodebarAg\Bexio\Requests\Accounts;

use CodebarAg\Bexio\Dto\Accounts\AccountDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfAccountsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 2000,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/accounts';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $accounts = collect();

        foreach ($res as $account) {
            $accounts->push(AccountDTO::fromArray($account));
        }

        return $accounts;
    }
}
