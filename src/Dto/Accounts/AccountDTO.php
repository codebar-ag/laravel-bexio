<?php

namespace CodebarAg\Bexio\Dto\Accounts;

use CodebarAg\Bexio\Enums\Accounts\AccountTypeEnum;
use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class AccountDTO extends Data
{
    public function __construct(
        public int $id,
        public string $account_no,
        public string $name,
        public int $account_type,
        public AccountTypeEnum $account_type_enum,
        public bool $is_active,
        public bool $is_locked,
        public ?int $tax_id = null,
        public ?int $fibu_account_group_id = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to create DTO from Response');
        }

        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        if (! $data) {
            throw new Exception('Unable to create DTO. Data missing from response.');
        }

        return new self(
            id: Arr::get($data, 'id'),
            account_no: Arr::get($data, 'account_no'),
            name: Arr::get($data, 'name'),
            account_type: Arr::get($data, 'account_type'),
            account_type_enum: AccountTypeEnum::from(Arr::get($data, 'account_type')),
            is_active: Arr::get($data, 'is_active'),
            is_locked: Arr::get($data, 'is_locked'),
            tax_id: Arr::get($data, 'tax_id'),
            fibu_account_group_id: Arr::get($data, 'fibu_account_group_id'),
        );
    }
}
