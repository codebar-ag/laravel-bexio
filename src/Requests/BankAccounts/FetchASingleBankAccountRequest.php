<?php

namespace CodebarAg\Bexio\Requests\BankAccounts;

use CodebarAg\Bexio\Dto\BankAccounts\BankAccountDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchASingleBankAccountRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
        readonly bool $show_archived = false,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/accounts/'.$this->id;
    }

    public function defaultQuery(): array
    {
        return [
            'show_archived' => $this->show_archived,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return BankAccountDTO::fromResponse($response);
    }
}
