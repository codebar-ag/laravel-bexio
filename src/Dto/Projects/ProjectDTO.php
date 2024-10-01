<?php

namespace CodebarAg\Bexio\Dto\Projects;

use Exception;
use Illuminate\Support\Arr;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class ProjectDTO extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $nr,
        public string $name,
        public string $start_date,
        public string $end_date,
        public string $comment,
        public int $pr_state_id,
        public int $pr_project_type_id,
        public int $contact_id,
        public ?int $contact_sub_id,
        public ?int $pr_invoice_type_id,
        public string $pr_invoice_type_amount,
        public null|int|float $pr_budget_type_id,
        public string $pr_budget_type_amount,
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
            uuid: Arr::get($data, 'uuid'),
            nr: Arr::get($data, 'nr'),
            name: Arr::get($data, 'name'),
            start_date: Arr::get($data, 'start_date'),
            end_date: Arr::get($data, 'end_date'),
            comment: Arr::get($data, 'comment'),
            pr_state_id: Arr::get($data, 'pr_state_id'),
            pr_project_type_id: Arr::get($data, 'pr_project_type_id'),
            contact_id: Arr::get($data, 'contact_id'),
            contact_sub_id: Arr::get($data, 'contact_sub_id'),
            pr_invoice_type_id: Arr::get($data, 'pr_invoice_type_id'),
            pr_invoice_type_amount: Arr::get($data, 'pr_invoice_type_amount'),
            pr_budget_type_id: Arr::get($data, 'pr_budget_type_id'),
            pr_budget_type_amount: Arr::get($data, 'pr_budget_type_amount'),
        );
    }
}
