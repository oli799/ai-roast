
<?php

use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;

class CreateRoast extends SoloRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'https://api.example.com/roasts';
    }
}
