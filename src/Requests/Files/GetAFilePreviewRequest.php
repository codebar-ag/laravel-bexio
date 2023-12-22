<?php

namespace CodebarAg\Bexio\Requests\Files;

use CodebarAg\Bexio\Dto\Files\FileDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAFilePreviewRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/files/'.$this->id.'/preview';
    }
}
