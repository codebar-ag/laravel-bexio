<?php

namespace CodebarAg\Zendesk\Requests;

use CodebarAg\Zendesk\Dto\Tickets\Attachments\UploadDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasStreamBody;

class CreateAttachmentRequest extends Request implements HasBody
{
    use HasStreamBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/uploads.json?filename='.$this->fileName;
    }

    public function __construct(
        protected string $fileName,
        protected string $mimeType,
        protected mixed $stream,
    ) {
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => $this->mimeType,
            'Accept' => 'application/json',
        ];
    }

    protected function defaultBody(): mixed
    {
        return $this->stream;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return UploadDTO::fromResponse($response);
    }
}
