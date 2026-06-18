<?php

namespace CodebarAg\Bexio\Requests\Invoices\Reminders;

use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ShowPdfAReminderRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $invoice_id,
        public readonly int $reminder_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/kb_reminder/'.$this->reminder_id.'/pdf';
    }

    public function createDtoFromResponse(Response $response): PdfDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return PdfDTO::fromArray($response->json());
    }
}
