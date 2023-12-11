<?php

namespace CodebarAg\Bexio\Dto\AccountGroups;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class AccountGroupDTO extends Data
{
    public function __construct(
        public int $id,
        public string $account_no,
        public string $name,
        public bool $is_active,
        public bool $is_locked,
        public ?int $parent_fibu_account_group_id = null,
    ) {
    }

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
            is_active: Arr::get($data, 'is_active'),
            is_locked: Arr::get($data, 'is_locked'),
            parent_fibu_account_group_id: Arr::get($data, 'parent_fibu_account_group_id'),
        );
    }
}
