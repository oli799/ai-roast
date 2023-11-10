<?php

namespace App\Jobs;

use App\Enums\DimensionEnum;
use App\Http\Requests\CreateRoast as CreateRoastRequest;
use App\Http\Requests\CreateScreenshot;
use App\Models\Payment;
use App\Models\User;
use DOMDocument;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateRoast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tires;

    public int $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Payment $payment,
    ) {
        $this->tires = app()->environment('production') ? 3 : 1;
        $this->timeout = app()->environment('production') ? 120 : 60;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $prompt = $this->getPrompt($this->payment->url);
            $images = $this->getImagesArray();

            $roastRequest = new CreateRoastRequest(config('openai.api_key'), $prompt, $images);
            $roastResponse = $roastRequest->send();

            $this->payment->update([
                'roast' => $roastResponse->json('choices.0.message.content'),
            ]);

            Log::info("Roast is ready for payment {$this->payment->id}.");
        } catch (Throwable $th) {
            if ($this->attempts() > $this->tires - 1) {
                if ($admin = User::query()->where('email', config('app.admin_email'))->first()) {
                    Notification::make()
                        ->title('Roast creation failed.')
                        ->body("Roast creation failed for payment {$this->payment->id}.")
                        ->danger()
                        ->sendToDatabase($admin);
                }

                Log::error("Roast creation failed for payment {$this->payment->id}. Error: ".$th->getMessage());
                throw $th;
            }

            $this->release($this->timeout);

            return;
        }
    }

    private function getPrompt(string $url): string
    {
        $prompt = 'You are a designer, marketing and seo expert. Create a 100 word constructive feedback in terms of desing, seo and overall user experience for the following website.';
        $prompt .= "The website is: {$url}.";

        if ($pageDescription = $this->getMetaTagValue($url, 'description')) {
            $prompt .= "The website meta description is: {$pageDescription}.";
        }

        if ($pageKeywords = $this->getMetaTagValue($url, 'keywords')) {
            $prompt .= "The website meta keywords are: {$pageKeywords}.";
        }

        $prompt .= 'At the end of the feedback create bullet point lists with 3-5 bullet point for each from the suggested imporvements in the following categories: Design, SEO, UX, UI.';
        $prompt .= 'Explain the top 5 most required imporvements and propose concrete solutions for each with links and specific informations.';
        $prompt .= 'The first photo is a screenshot of the website on a phone and the second is a screenshot of the website on a computer.';

        return $prompt.'Return the feedback in markdow format.';
    }

    /**
     * @return array<int,array<string,array<string,string>|string>>
     */
    private function getImagesArray(): array
    {
        $images = [];
        $phoneImageUrl = $this->getScreenshotUrl($this->payment->url, DimensionEnum::PHONE);

        $images[] = [
            'type' => 'image_url',
            'image_url' => [
                'url' => $phoneImageUrl,
            ],
        ];

        $computerImageUrl = $this->getScreenshotUrl($this->payment->url, DimensionEnum::COMPUTER);

        $images[] = [
            'type' => 'image_url',
            'image_url' => [
                'url' => $computerImageUrl,
            ],
        ];

        return $images;
    }

    private function getScreenshotUrl(string $url, DimensionEnum $dimension): string
    {
        $screenshotRequest = new CreateScreenshot(config('apiflash.api_key'), $url, $dimension);
        $screenshotResponse = $screenshotRequest->send();

        return $screenshotResponse->json('url');
    }

    private function getMetaTagValue(string $url, string $metaTagName): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            $html = (string) $response->body();
        } catch (Throwable) {
            Log::error("Failed to get open {$url}.");

            return null;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $metaTags = $dom->getElementsByTagName('meta');
        $metaTagValue = null;

        if (collect($metaTags)->count() === 0) {
            return null;
        }

        foreach ($metaTags as $tag) {
            if ($tag->getAttribute('name') === $metaTagName) {
                $metaTagValue = $tag->getAttribute('content');
                break;
            }
        }

        return $metaTagValue;
    }
}
