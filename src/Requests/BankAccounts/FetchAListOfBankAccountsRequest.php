<?php

namespace CodebarAg\Bexio\Requests\BankAccounts;

use CodebarAg\Bexio\Dto\BankAccounts\BankAccountDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfBankAccountsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/accounts';
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

        $bankAccounts = collect();

        foreach ($res as $bankAccount) {
            $bankAccounts->push(BankAccountDTO::fromArray($bankAccount));
        }

        return $bankAccounts;
    }
}
