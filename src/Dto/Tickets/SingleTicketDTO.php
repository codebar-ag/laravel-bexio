<?php

namespace CodebarAg\Zendesk\Dto\Tickets;

use CodebarAg\Zendesk\Dto\Tickets\Comments\CommentDTO;
use CodebarAg\Zendesk\Enums\TicketPriority;
use CodebarAg\Zendesk\Enums\TicketType;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Saloon\Http\Response;
use Spatie\LaravelData\Data;

class SingleTicketDTO extends Data
{
    public function __construct(
        public ?bool $allow_attachments,
        public ?bool $allow_channelback,
        public ?string $assignee_email,
        public ?int $assignee_id,
        public ?array $attribute_value_ids,
        public ?int $brand_id,
        public ?array $collaborator_ids,
        public ?array $collaborators,
        public ?CommentDTO $comment,
        public ?Carbon $created_at,
        public ?array $custom_fields,
        public ?string $description,
        public ?Carbon $due_at,
        public ?array $email_cc_ids,
        public ?array $email_ccs,
        public ?string $external_id,
        public ?array $follower_ids,
        public ?array $followers,
        public ?array $followup_ids,
        public ?int $forum_topic_id,
        public ?bool $from_messaging_channel,
        public ?int $group_id,
        public ?bool $has_incidents,
        public ?int $id,
        public ?bool $is_public,
        public ?int $macro_id,
        public ?array $macro_ids,
        public ?array $metadata,
        public ?int $organization_id,
        public ?TicketPriority $priority,
        public ?int $problem_id,
        public ?string $raw_subject,
        public ?string $recipient,
        public ?array $requester,
        public ?int $requester_id,
        public ?bool $self_update,
        public ?array $satisfaction_rating,
        public ?array $sharing_agreement_ids,
        public ?string $status,
        public ?string $subject,
        public ?int $submitter_id,
        public ?array $tags,
        public ?int $ticket_form_id,
        public ?TicketType $type,
        public ?Carbon $updated_at,
        public ?Carbon $updated_stamp,
        public ?string $url,
        public ?array $via,
        public ?int $via_followup_source_id,
        public ?int $via_id,
        public ?array $voice_comment,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        if ($response->failed()) {
            throw new \Exception('Failed to get a single ticket', $response->status());
        }

        $data = Arr::get($response->json(), 'ticket');

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $comment = Arr::get($data, 'comment');

        if ($comment && ! $comment instanceof CommentDTO) {
            $comment = CommentDTO::fromArray($comment);
        }

        $priority = Arr::get($data, 'priority');

        if ($priority && ! $priority instanceof TicketPriority) {
            $priority = TicketPriority::tryFrom($priority);
        }

        $type = Arr::get($data, 'type');

        if ($type && ! $type instanceof TicketType) {
            $type = TicketType::tryFrom($type);
        }

        return new self(
            allow_attachments: Arr::get($data, 'allow_attachments'),
            allow_channelback: Arr::get($data, 'allow_channelback'),
            assignee_email: Arr::get($data, 'assignee_email'),
            assignee_id: Arr::get($data, 'assignee_id'),
            attribute_value_ids: Arr::get($data, 'attribute_value_ids'),
            brand_id: Arr::get($data, 'brand_id'),
            collaborator_ids: Arr::get($data, 'collaborator_ids'),
            collaborators: Arr::get($data, 'collaborators'),
            comment: $comment,
            created_at: Carbon::parse(Arr::get($data, 'created_at')),
            custom_fields: Arr::get($data, 'custom_fields'),
            description: Arr::get($data, 'description'),
            due_at: Carbon::parse(Arr::get($data, 'due_at')),
            email_cc_ids: Arr::get($data, 'email_cc_ids'),
            email_ccs: Arr::get($data, 'email_ccs'),
            external_id: Arr::get($data, 'external_id'),
            follower_ids: Arr::get($data, 'follower_ids'),
            followers: Arr::get($data, 'followers'),
            followup_ids: Arr::get($data, 'followup_ids'),
            forum_topic_id: Arr::get($data, 'forum_topic_id'),
            from_messaging_channel: Arr::get($data, 'from_messaging_channel'),
            group_id: Arr::get($data, 'group_id'),
            has_incidents: Arr::get($data, 'has_incidents'),
            id: Arr::get($data, 'id'),
            is_public: Arr::get($data, 'is_public'),
            macro_id: Arr::get($data, 'macro_id'),
            macro_ids: Arr::get($data, 'macro_ids'),
            metadata: Arr::get($data, 'metadata'),
            organization_id: Arr::get($data, 'organization_id'),
            priority: $priority,
            problem_id: Arr::get($data, 'problem_id'),
            raw_subject: Arr::get($data, 'raw_subject'),
            recipient: Arr::get($data, 'recipient'),
            requester: Arr::get($data, 'requester'),
            requester_id: Arr::get($data, 'requester_id'),
            self_update: Arr::get($data, 'self_update'),
            satisfaction_rating: Arr::get($data, 'satisfaction_rating'),
            sharing_agreement_ids: Arr::get($data, 'sharing_agreement_ids'),
            status: Arr::get($data, 'status'),
            subject: Arr::get($data, 'subject'),
            submitter_id: Arr::get($data, 'submitter_id'),
            tags: Arr::get($data, 'tags'),
            ticket_form_id: Arr::get($data, 'ticket_form_id'),
            type: $type,
            updated_at: Carbon::parse(Arr::get($data, 'updated_at')),
            updated_stamp: Arr::get($data, 'updated_stamp'),
            url: Arr::get($data, 'url'),
            via: Arr::get($data, 'via'),
            via_followup_source_id: Arr::get($data, 'via_followup_source_id'),
            via_id: Arr::get($data, 'via_id'),
            voice_comment: Arr::get($data, 'voice_comment'),
        );
    }
}
