<img src="https://banners.beyondco.de/Laravel%20Zendesk.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-zendesk&pattern=circuitBoard&style=style_2&description=A+Laravel+Zendesk+integration.&md=1&showWatermark=1&fontSize=150px&images=home&widths=500&heights=500">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-zendesk.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-zendesk)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-zendesk.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-zendesk)
[![run-tests](https://github.com/codebar-ag/laravel-zendesk/actions/workflows/run-tests.yml/badge.svg)](https://github.com/codebar-ag/laravel-zendesk/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/codebar-ag/laravel-zendesk/actions/workflows/phpstan.yml/badge.svg)](https://github.com/codebar-ag/laravel-zendesk/actions/workflows/phpstan.yml)

This package was developed to give you a quick start to creating tickets via the Zendesk API.

## 💡 What is Zendesk?

Zendesk is a cloud-based help desk management solution offering customizable tools to build customer service portals,
knowledge base and online communities.

## 🛠 Requirements

| Package 	 | PHP 	 | Laravel 	      | Zendesk 	 |
|-----------|-------|----------------|:---------:|
| >v1.0     | >8.2  | > Laravel 10.0 |     ✅     |

## Authentication

The currently supported authentication methods are:

| Method 	           | Supported 	 |
|--------------------|:-----------:|
| Basic Auth         |      ✅      |
| API token          |      ✅      |
| OAuth access token |      ❌      |

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-zendesk
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="CodebarAg\Zendesk\ZendeskServiceProvider" --tag="config"
```

You can add the following env variables to your `.env` file:

```dotenv
ZENDESK_SUBDOMAIN=your-subdomain #required
ZENDESK_AUTHENTICATION_METHOD=token #default ['basic', 'token']
ZENDESK_EMAIL_ADDRESS=test@example.com #required
ZENDESK_API_TOKEN=your-api-token #required only for token authentication
ZENDESK_API_PASSWORD=your-password #required only for basic authentication
```

`Note: We handle base64 encoding for you so you don't have to encode your credentials.`

You can retrieve your API token from
your [Zendesk Dashboard](https://developer.zendesk.com/api-reference/introduction/security-and-auth/)

## Usage

To use the package, you need to create a ZendeskConnector instance.

```php
use CodebarAg\Zendesk\ZendeskConnector;
...

$connector = new ZendeskConnector();
````

### Requests

The following requests are currently supported:

| Request 	         | Supported 	 |
|-------------------|:-----------:|
| List Tickets      |      ✅      |
| Count Tickets     |      ✅      |
| Show Ticket       |      ✅      |
| Create Ticket     |      ✅      |
| Create Attachment |      ✅      |

### Responses

The following responses are currently supported for retrieving the response body:

| Response Methods	 | Description                                                                                                                        | Supported 	 |
|-------------------|------------------------------------------------------------------------------------------------------------------------------------|:-----------:|
| body              | Returns the HTTP body as a string                                                                                                  |      ✅      |
| json              | Retrieves a JSON response body and json_decodes it into an array.                                                                  |      ✅      |
| object            | Retrieves a JSON response body and json_decodes it into an object.                                                                 |      ✅      |
| collect           | Retrieves a JSON response body and json_decodes it into a Laravel collection. **Requires illuminate/collections to be installed.** |      ✅      |
| dto               | Converts the response into a data-transfer object. You must define your DTO first                                                  |      ✅      |

See https://docs.saloon.dev/the-basics/responses for more information.

### Enums

We provide enums for the following values:

| Enum 	            |                               Values 	                                |
|-------------------|:---------------------------------------------------------------------:|
| TicketPriority    |                   'urgent', 'high', 'normal', 'low'                   |
| TicketType        |               'incident', 'problem', 'question', 'task'               |
| MalwareScanResult | 'malware_found', 'malware_not_found', 'failed_to_scan', 'not_scanned' |

`Note: When using the dto method on a response, the enum values will be converted to their respective enum class.`

### DTOs

We provide DTOs for the following:

| DTO 	           |
|-----------------|
| AttachmentDTO   |
| ThumbnailDTO    |
| UploadDTO       |
| CommentDTO      |
| AllTicketsDTO   |
| CountTicketsDTO |
| SingleTicketDTO |

`Note: This is the prefered method of interfacing with Requests and Responses however you can still use the json, object and collect methods. and pass arrays to the requests.`

### Examples

#### Create a ticket

```php
use CodebarAg\Zendesk\Requests\CreateSingleTicketRequest;
use CodebarAg\Zendesk\DTOs\SingleTicketDTO;
use CodebarAg\Zendesk\DTOs\CommentDTO;
use CodebarAg\Zendesk\Enums\TicketPriority;
...

$ticketResponse = $connector->send(
    new CreateSingleTicketRequest(
        SingleTicketDTO::fromArray([
            'comment' => CommentDTO::fromArray([
                'body' => 'The smoke is very colorful.',
            ]),
            'priority' => TicketPriority::URGENT,
            "subject" => "My printer is on fire!",
            "custom_fields" => [
                [
                    "id" => 12345678910111,
                    "value" => "Your custom field value"
                ],
                [
                    "id" => 12345678910112,
                    "value" => "Your custom field value 2"
                ],
            ],
        ])
    )
);

$ticket = $ticketResponse->dto();
````

#### List all tickets

```php
use CodebarAg\Zendesk\Requests\AllTicketsRequest;
...

$listTicketResponse = $connector->send(new AllTicketsRequest());
$listTicketResponse->dto();
````

#### Count all tickets

```php
use CodebarAg\Zendesk\Requests\CountTicketsRequest;
...

$countTicketResponse = $connector->send(new CountTicketsRequest());
$countTicketResponse->dto();
````

#### Show a ticket

```php
use CodebarAg\Zendesk\Requests\ShowTicketRequest;
...

$ticketID = 1;

$showTicketResponse = $connector->send(new ShowTicketRequest($ticketID));
$showTicketResponse->dto();
````

#### Upload an attachment

```php
use CodebarAg\Zendesk\Requests\CreateAttachmentRequest;
use CodebarAg\Zendesk\Requests\CreateSingleTicketRequest;
use Illuminate\Support\Facades\Storage;

$uploadResponse = $connector->send(
    new CreateAttachmentRequest(
        fileName: 'someimage.png',
        mimeType: Storage::disk('local')->mimeType('public/someimage.png'),
        stream: Storage::disk('local')->readStream('public/someimage.png')
    )
);

$token = $uploadResponse->dto()->token;

$ticketResponse = $connector->send(
    new CreateSingleTicketRequest(
        SingleTicketDTO::fromArray([
            'comment' => CommentDTO::fromArray([
                ...
                'uploads' => [
                    $token,
                ],
            ]),
        ])
    )
);

$ticket = $ticketResponse->dto();
```

## 🚧 Testing

Copy your own phpunit.xml-file.

```bash
cp phpunit.xml.dist phpunit.xml
```

Run the tests:

```bash
./vendor/bin/pest
```

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## ✏️ Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

```bash
composer test
```

### Code Style

```bash
./vendor/bin/pint
```

## 🧑‍💻 Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on reporting security vulnerabilities.

## 🙏 Credits
- [Rhys Lees](https://github.com/RhysLees)
- [Sebastian Fix](https://github.com/StanBarrows)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)
- [Laravel Saloon by Sam Carré](https://github.com/Sammyjo20/Saloon)

## 🎭 License

The MIT License (MIT). Please have a look at [License File](LICENSE.md) for more information.
# laravel-bexio
# laravel-bexio
