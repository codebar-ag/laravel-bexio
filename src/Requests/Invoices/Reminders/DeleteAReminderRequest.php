<?php

namespace CodebarAg\Bexio\Requests\Invoices\Reminders;

use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DeleteAReminderRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $invoice_id,
        public readonly int $reminder_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/kb_reminder/'.$this->reminder_id;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
