<?php

namespace CodebarAg\Bexio\Requests\Invoices\Reminders;

use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SendAReminderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array  $payload  Optional body, e.g. recipient_emails, subject, message, mark_as_sent.
     */
    public function __construct(
        public readonly int $invoice_id,
        public readonly int $reminder_id,
        public readonly array $payload = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/kb_reminder/'.$this->reminder_id.'/send';
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return $response->json();
    }
}
