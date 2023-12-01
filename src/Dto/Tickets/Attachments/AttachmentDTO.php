<?php

namespace CodebarAg\Zendesk\Dto\Tickets\Attachments;

use CodebarAg\Zendesk\Enums\MalwareScanResult;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

class AttachmentDTO extends Data
{
    public function __construct(
        public ?string $content_type,
        public ?string $content_url,
        public ?bool $deleted,
        public ?string $file_name,
        public ?string $height,
        public ?int $id,
        public ?bool $inline,
        public ?bool $malware_access_override,
        public ?MalwareScanResult $malware_scan_result,
        public ?string $mapped_content_url,
        public ?int $size,
        public ?array $thumbnails,
        public ?string $url,
        public ?string $width,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $thumbnails = Arr::get($data, 'thumbnails');

        if ($thumbnails) {
            foreach ($thumbnails as $key => $thumbnail) {
                $thumbnails[$key] = self::getThumbnail($thumbnail);
            }
        }

        return new self(
            content_type: Arr::get($data, 'content_type'),
            content_url: Arr::get($data, 'content_url'),
            deleted: Arr::get($data, 'deleted'),
            file_name: Arr::get($data, 'file_name'),
            height: Arr::get($data, 'height'),
            id: Arr::get($data, 'id'),
            inline: Arr::get($data, 'inline'),
            malware_access_override: Arr::get($data, 'malware_access_override'),
            malware_scan_result: MalwareScanResult::tryFrom(Arr::get($data, 'malware_scan_result')),
            mapped_content_url: Arr::get($data, 'mapped_content_url'),
            size: Arr::get($data, 'size'),
            thumbnails: $thumbnails ?? null,
            url: Arr::get($data, 'url'),
            width: Arr::get($data, 'width'),
        );
    }

    private static function getThumbnail(null|array|ThumbnailDTO $data): ThumbnailDTO
    {
        $attachment = $data ?? null;

        if (! $attachment instanceof ThumbnailDTO) {
            $attachment = ThumbnailDTO::fromArray($attachment);
        }

        return $attachment;
    }
}
