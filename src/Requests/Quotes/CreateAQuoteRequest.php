<?php

namespace CodebarAg\Bexio\Requests\Quotes;

use CodebarAg\Bexio\Dto\ItemPositions\Abstractions\OfferPositionDTO;
use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAQuoteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly ?QuoteDTO $quote = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/kb_offer';
    }

    public function defaultBody(): array
    {
        if ($this->quote) {
            $quote = collect($this->quote->toArray());

            return $this->filterQuote($quote);
        }

        return [];
    }

    protected function filterQuote(Collection $quote): array
    {
        $filteredQuote = $quote->only(keys: [
            'title',
            'contact_id',
            'contact_sub_id',
            'user_id',
            'pr_project_id',
            'logopaper_id',
            'language_id',
            'bank_account_id',
            'currency_id',
            'payment_type_id',
            'header',
            'footer',
            'mwst_type',
            'mwst_is_net',
            'show_position_taxes',
            'is_valid_from',
            'is_valid_until',
            'api_reference',
            'viewed_by_client_at',
            'template_slug',
            'positions',
        ]);

        $positions = $quote->get('positions');
        if ($positions) {
            $filteredQuote->put('positions', $this->filterPositions(collect($positions)));
        }

        return $filteredQuote->toArray();
    }

    protected function filterPositions(Collection $positions): array
    {
        $allowedKeys = [
            'KbPositionCustom' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
            ],
            'KbPositionArticle' => [
                'amount',
                'unit_id',
                'account_id',
                'tax_id',
                'text',
                'unit_price',
                'discount_in_percent',
                'article_id',
            ],
            'KbPositionText' => [
                'text',
                'show_pos_nr',
            ],
            'KbPositionSubtotal' => [
                'text',
            ],
            'KbPositionPagebreak' => [
                'pagebreak',
            ],
            'KbPositionDiscount' => [
                'text',
                'is_percentual',
                'value',
            ],
        ];

        return $positions->map(function (OfferPositionDTO|array $position) use ($allowedKeys) {
            // Handle both OfferPositionDTO objects and arrays
            $positionData = $position instanceof OfferPositionDTO ? $position->toArray() : $position;
            $positionCollection = collect($positionData);
            $type = $positionCollection->get('type');

            if (! isset($allowedKeys[$type])) {
                return $positionCollection->toArray();
            }

            return $positionCollection->only(
                array_merge(['type'], $allowedKeys[$type])
            )->filter(fn ($value) => $value !== null)->toArray();
        })->toArray();
    }

    public function createDtoFromResponse(Response $response): QuoteDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        return QuoteDTO::fromArray($res);
    }
}
