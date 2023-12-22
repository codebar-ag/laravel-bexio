<?php

namespace CodebarAg\Bexio\Requests\QrPayments;

use CodebarAg\Bexio\Dto\ManualEntries\CreateManualEntryDTO;
use CodebarAg\Bexio\Dto\QrPayments\CreateEditQrPaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\QrPaymentDTO;
use Exception;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateQrPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $bank_account_id,
        readonly array|CreateEditQrPaymentDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/qr_payments';
    }

    public function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditQrPaymentDTO) {
            $body = CreateEditQrPaymentDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return QrPaymentDTO::fromResponse($response);
    }
}
