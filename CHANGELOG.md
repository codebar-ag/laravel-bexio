# Changelog

All notable changes to `laravel-bexio` will be documented in this file.

## 20260618 | v14.0

This is a **MAJOR** release with breaking changes. All DTOs and fixtures were reconciled
against a live bexio instance (accounting period 2026).

### ⚠️ Breaking Changes

- **Removed `IbanPayments`**: Deleted all `IbanPayments` request classes
  (`GetIbanPaymentRequest`, `CreateIbanPaymentRequest`, `EditIbanPaymentRequest`) and the
  `CreateEditIbanPaymentDTO`. The underlying endpoints no longer exist in the bexio API.
- **Removed `QrPayments`**: Deleted all `QrPayments` request classes
  (`GetQrPaymentRequest`, `CreateQrPaymentRequest`, `EditQrPaymentRequest`) and the
  `CreateEditQrPaymentDTO`. The underlying endpoints no longer exist in the bexio API.
- **`Payments` migrated to the banking API**: `Payments` now targets `/4.0/banking/payments`.
  - `FetchAListOfPaymentsRequest(?string $filterBy, ?int $page, ?int $perPage)` returns a
    `Collection` of `PaymentDTO` (the wrapped `results` array is unwrapped automatically).
  - `DeleteAPaymentRequest(int|string $payment_id)` → `DELETE /4.0/banking/payments/{id}`.
  - `CancelAPaymentRequest(int|string $payment_id)` → `POST /4.0/banking/payments/{id}/cancel`.
  - `Payments\PaymentDTO` was rewritten with the new banking shape (id, uuid, sender,
    recipient, amount, currency, execution_date, allowance, is_salary, instruction_id,
    purchase_reference, document_no, qr_reference_number, additional_information, status,
    type, due_date, created_at, is_editing_restricted).
- **`ItemPositions` are now document-scoped article positions**: requests target
  `/2.0/{kb_document_type}/{document_id}/kb_position_article[/{position_id}]` and have new
  constructor signatures:
  - `FetchAListOfItemPositionsRequest(string $kb_document_type, int $document_id)`
  - `FetchAnItemPositionRequest(string $kb_document_type, int $document_id, int $item_position_id)`
  - `CreateAnItemPositionRequest(string $kb_document_type, int $document_id, ?CreateEditItemPositionDTO $itemPosition)`
  - `EditAnItemPositionRequest(string $kb_document_type, int $document_id, int $item_position_id, ?CreateEditItemPositionDTO $itemPosition)`
  - `DeleteAnItemPositionRequest(string $kb_document_type, int $document_id, int $item_position_id)`
  - Creating a position requires `article_id`; the edit endpoint rejects `article_id`.
- **`EditAnItemRequest` now uses `POST`** (previously `PUT`) to match the bexio API.
- **DTO field corrections across many resources** to match the live API, including:
  - `AccountDTO` / `AccountGroupDTO`: added `uuid`, dropped `account_type_enum`.
  - `ContactDTO`: `salutation_form_id` → `salutation_form`.
  - `DocumentSettingDTO`: corrected `user_*` → `use_*` enumeration typo.
  - `TaxDTO`: added `start_month` / `end_month`.
  - Invoice `PaymentDTO` and `ReminderDTO` reshaped to match the API responses.
  - Additional field type/name fixes across other resources.

### 🧪 Tests

- Regenerated all Saloon test fixtures against a live bexio instance (accounting period 2026).

### 🔧 CI

- Workflows now force the Node 24 runtime via `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24`.

## 20251127

### Added REST APIs | v13.5

- **Quote REST API**: Full implementation of Quote endpoints - [Documentation](README.md#quotes)
- **ItemPosition REST API**: Full implementation of ItemPosition endpoints - [Documentation](README.md#item-positions)

### ⚠️ Breaking Changes

- **InvoicePositionDTO Abstraction**: Implemented an abstraction layer for `QuotePositionDTO` and `InvoicePositionDTO`
  - **Breaking Change**: Start using the new `InvoicePositionDTO` that extends `ItemPositionDTO`
  - The new structure provides better code reusability and consistency across position DTOs
  - `QuotePositionDTO` now also uses the `ItemPositionDTO` abstraction
  - See [Item Position DTOs documentation](README.md#item-positions) for migration details

### 🐛 Bug Fixes

- **AdditionalAddressDTO & CreateEditAdditionalAddressDTO**: Fixed `country_id` field type from `?string` to `?int` in `CreateEditAdditionalAddressDTO` to match the correct data type
  - The `country_id` field is now properly typed as `?int` (nullable integer) in both DTOs
  - Updated documentation examples to include `country_id` parameter
- **AdditionalAddressDTO**: Ensured `postcode` field is properly typed as `?string` (nullable string) to handle cases where the API may return integer values

## 20251126 | v13.3 & v13.4

### Added REST APIs

- **Countries REST API**: Full implementation of Countries endpoints - [Documentation](README.md#countries)
- **Items REST API**: Full implementation of Items endpoints - [Documentation](README.md#items)

## 20251110 | v13.2

### Removed ContactAdditionalAddresses

- **Removed** `ContactAdditionalAddressDTO` and `CreateEditContactAdditionalAddressDTO`
- **Removed** all `ContactAdditionalAddresses` request classes:
  - `CreateContactAdditionalAddressRequest`
  - `EditAContactAdditionalAddressRequest`
  - `FetchAContactAdditionalAddressRequest`
  - `FetchAListOfContactAdditionalAddressesRequest`
  - `SearchContactAdditionalAddressesRequest`
  - `DeleteAContactAdditionalAddressRequest`

**Migration:** Use `AdditionalAddresses` instead, which provides the same functionality with additional fields (`street_name`, `house_number`, `address_addition`, `name_addition`). All requests now use `contactId` as the parameter name for consistency.

### 🔄 DTO Field Updates

- **AdditionalAddresses requests:** Standardized parameter naming to use `contactId` instead of `id` for consistency across all requests. The following DTOs have been updated with new fields:

#### Contact & Additional Address Fields (2025-06-04)

- **ContactDTO** and **CreateEditContactDTO**: Added `street_name`, `house_number`, and `address_addition` fields
  - The `address` field is now deprecated in favor of the new structured address fields
  - These fields provide better address handling and validation

- **AdditionalAddressDTO** and **CreateEditAdditionalAddressDTO**: Added `street_name`, `house_number`, and `address_addition` fields
  - The `address` field is now deprecated in favor of the new structured address fields

#### Bank Account Fields (2025-06-06)

- **BankAccountDTO**: Added `owner_house_number` and `owner_country_code` fields
  - These fields provide more detailed owner information for bank accounts

#### Calendar Year Fields (2025-08-04)

- **CalendarYearDTO**: Added `is_annual_reporting` field
  - This field indicates whether the calendar year is used for annual reporting
