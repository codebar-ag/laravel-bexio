<?php

namespace CodebarAg\Bexio\Requests\Invoices\Reminders;

use CodebarAg\Bexio\Dto\Invoices\ReminderDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfRemindersRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $invoice_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/kb_reminder';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $reminders = collect();

        foreach ($response->json() as $reminder) {
            $reminders->push(ReminderDTO::fromArray($reminder));
        }

        return $reminders;
    }
}
