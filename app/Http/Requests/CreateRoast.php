<?php

namespace App\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CreateRoast extends SoloRequest implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int,array<string,array<string,string>|string>>  $images
     */
    public function __construct(
        protected string $apiToken,
        protected string $prompt,
        protected array $images,
    ) {
        $this->withTokenAuth($this->apiToken);
    }

    public function resolveEndpoint(): string
    {
        return 'https://api.openai.com/v1/chat/completions';
    }

    protected function defaultConfig(): array
    {
        return [
            'timeout' => 120,
        ];
    }

    /**
     * @return array<string,string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'model' => 'gpt-4-vision-preview',
            'max_tokens' => 2023,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->prompt,
                        ],
                        ...$this->images,
                    ],
                ],
            ],
        ];
    }
}
