# Changelog

All notable changes to `laravel-bexio` will be documented in this file.

## 20251110

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

