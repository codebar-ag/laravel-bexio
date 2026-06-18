<?php

namespace CodebarAg\Bexio\Requests\Invoices\Reminders;

use CodebarAg\Bexio\Dto\Invoices\ReminderDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAReminderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $invoice_id,
        public readonly ?ReminderDTO $reminder = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/kb_reminder';
    }

    public function defaultBody(): array
    {
        if ($this->reminder) {
            return $this->filterReminder(collect($this->reminder->toArray()));
        }

        return [];
    }

    protected function filterReminder(Collection $reminder): array
    {
        return $reminder->only([
            'title',
            'reminder_level_id',
            'is_valid_from',
            'is_valid_to',
            'subject',
            'body',
            'salutation_id',
        ])->toArray();
    }

    public function createDtoFromResponse(Response $response): ReminderDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ReminderDTO::fromArray($response->json());
    }
}
