# Changelog

All notable changes to `laravel-bexio` will be documented in this file.

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
