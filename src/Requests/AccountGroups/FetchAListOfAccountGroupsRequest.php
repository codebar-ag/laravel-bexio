<?php

namespace CodebarAg\Bexio\Requests\AccountGroups;

use CodebarAg\Bexio\Dto\AccountGroups\AccountGroupDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfAccountGroupsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $limit = 2000,
        public readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/account_groups';
    }

    public function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $accountGroups = collect();

        foreach ($res as $accountGroup) {
            $accountGroups->push(AccountGroupDTO::fromArray($accountGroup));
        }

        return $accountGroups;
    }
}
