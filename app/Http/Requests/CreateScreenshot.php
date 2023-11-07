<?php

namespace App\Http\Requests;

use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;
use Saloon\Traits\Plugins\AcceptsJson;

class CreateScreenshot extends SoloRequest
{
    use AcceptsJson;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $apiToken,
        protected string $url
    ) {
        $this->withTokenAuth($this->apiToken);
    }

    public function resolveEndpoint(): string
    {
        return 'https://api.apiflash.com/v1/urltoimage';
    }

    protected function defaultQuery(): array
    {
        return [
            'access_key' => $this->apiToken,
            'url' => $this->url,
            'format' => 'webp',
            'width' => 1920,
            'full_page' => true,
            'quality' => 75,
            'scroll_page' => true,
            'response_type' => 'json',
            'no_ads' => true,
        ];
    }
}
