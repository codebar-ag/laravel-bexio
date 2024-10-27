<?php

namespace CodebarAg\Bexio\Dto\BusinessActivities;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class BusinessActivityDTO extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?bool $default_is_billable,
        public ?float $default_price_per_hour,
        public ?int $account_id,
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
            name: Arr::get($data, 'name'),
            default_is_billable: Arr::get($data, 'default_is_billable'),
            default_price_per_hour: Arr::get($data, 'default_price_per_hour'),
            account_id: Arr::get($data, 'account_id'),
        );
    }
}
