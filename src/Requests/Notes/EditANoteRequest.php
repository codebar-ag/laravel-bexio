<?php

namespace CodebarAg\Bexio\Requests\Notes;

use CodebarAg\Bexio\Dto\Notes\CreateEditNoteDTO;
use CodebarAg\Bexio\Dto\Notes\NoteDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class EditANoteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $id,
        readonly protected array|CreateEditNoteDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/note/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditNoteDTO) {
            $body = CreateEditNoteDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): NoteDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return NoteDTO::fromArray($response->json());
    }
}
