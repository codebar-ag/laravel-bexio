<?php

namespace CodebarAg\Bexio\Requests\Invoices\Payments;

use CodebarAg\Bexio\Dto\Invoices\PaymentDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $invoice_id,
        public readonly ?PaymentDTO $payment = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_invoice/'.$this->invoice_id.'/payment';
    }

    public function defaultBody(): array
    {
        if ($this->payment) {
            return $this->filterPayment(collect($this->payment->toArray()));
        }

        return [];
    }

    protected function filterPayment(Collection $payment): array
    {
        return $payment->only([
            'date',
            'value',
            'bank_account_id',
            'payment_service_id',
        ])->filter(fn ($value) => $value !== null)->toArray();
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return PaymentDTO::fromArray($response->json());
    }
}
