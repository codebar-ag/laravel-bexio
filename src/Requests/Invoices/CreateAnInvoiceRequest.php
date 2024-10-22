<?php

namespace CodebarAg\Bexio\Requests\Invoices;

use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAnInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $invoices = collect();

        foreach ($res as $invoice) {
            $invoices->push(InvoiceDTO::fromArray($invoice));
        }

        return $invoices;
    }
}
