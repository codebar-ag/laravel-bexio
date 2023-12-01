<?php

use CodebarAg\Zendesk\Requests\CreateAttachmentRequest;
use CodebarAg\Zendesk\ZendeskConnector;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can create an attachment', closure: function () {
    $mockClient = new MockClient([
        CreateAttachmentRequest::class => MockResponse::fixture('create-attachment-request'),
    ]);

    $connector = new ZendeskConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(
        new CreateAttachmentRequest(
            fileName: 'test.png',
            mimeType: 'image/png',
            stream: fopen(__DIR__.'/../Fixtures/Files/test.png', 'r')
        )
    );

    $mockClient->assertSent(CreateAttachmentRequest::class);

    expect($response->dto()->token)->toBe('cs0vflvMytl7PHs8XueOo5mty')
        ->and($response->dto()->attachment->content_type)->toBe('image/png')
        ->and($response->dto()->attachment->size)->toBe(26271)
        ->and($response->dto()->attachment->file_name)->toBe('test.png')
        ->and($response->dto()->attachment->width)->toBe('640')
        ->and($response->dto()->attachment->height)->toBe('360')
        ->and($response->dto()->attachment->content_url)->toBe('https://codebarsolutions.zendesk.com/attachments/token/K32w31jO8UBB37UpKk2i1o4wg/?name=test.png');
});
