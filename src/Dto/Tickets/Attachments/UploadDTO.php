<?php

namespace CodebarAg\Zendesk\Dto\Tickets\Attachments;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class UploadDTO extends Data
{
    public function __construct(
        public string $token,
        public Carbon $expires_at,
        public array $attachments,
        public AttachmentDTO $attachment,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = Arr::get($response->json(), 'upload');

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $attachments = Arr::get($data, 'attachments');

        if ($attachments) {
            foreach ($attachments as $key => $attachment) {
                $attachments[$key] = self::getAttachment($attachment);
            }
        }

        return new self(
            token: Arr::get($data, 'token'),
            expires_at: Carbon::parse(Arr::get($data, 'expires_at')),
            attachments: $attachments,
            attachment: self::getAttachment(Arr::get($data, 'attachment')),
        );
    }

    private static function getAttachment(null|array|AttachmentDTO $data): AttachmentDTO
    {
        $attachment = $data ?? null;

        if (! $attachment instanceof AttachmentDTO) {
            $attachment = AttachmentDTO::fromArray($attachment);
        }

        return $attachment;
    }
}
