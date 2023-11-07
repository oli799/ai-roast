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

    public function __construct(
        protected string $apiToken,
        protected string $imageUrl
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
        $prompt = 'You are a fullstack webdeveloper and marketing expert. Create a 100 word constructive feedback in terms of desing, marketing and overall user experience for the following website.';
        $prompt .= "At the end of the feedback create a 10-20 bullet point list from the suggested imporvements. Explain the top 5 most required imporvements and propose concrete solutions for each with links and specific informations. \n\n";

        return [
            'model' => 'gpt-4-vision-preview',
            'max_tokens' => 1000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $this->imageUrl,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
