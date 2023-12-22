<?php

namespace CodebarAg\Bexio\Requests\IbanPayments;

use CodebarAg\Bexio\Dto\IbanPayments\CreateEditIbanPaymentDTO;
use CodebarAg\Bexio\Dto\IbanPayments\IbanPaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\CreateEditQrPaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\QrPaymentDTO;
use Exception;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditIbanPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        readonly int $bank_account_id,
        readonly int $payment_id,
        readonly int $iban,
        readonly int $id,
        readonly array|CreateEditIbanPaymentDTO $data,
    ) {
    }

    public function defaultQuery(): array
    {
        return [
            'iban' => $this->iban,
            'id' => $this->id,
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/banking/bank_accounts/'.$this->bank_account_id.'/iban_payments/'.$this->payment_id;
    }

    public function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditIbanPaymentDTO) {
            $body = CreateEditIbanPaymentDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return IbanPaymentDTO::fromResponse($response);
    }
}
