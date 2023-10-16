<?php

namespace App\Jobs;

use App\Models\Payment;
use DOMDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
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
        $this->timeout = app()->environment('production') ? 120 : 30;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $prompt = "You are a fullstack webdeveloper and marketing expert. Create a 100 word constructive feedback in terms of desing, marketing and overall user experience for the following website: {$this->payment->url}.";

            if ($pageDescription = $this->getMetaTagValue($this->payment->url, 'description')) {
                $prompt .= "The website description is: {$pageDescription}.";
            }

            if ($pageKeywords = $this->getMetaTagValue($this->payment->url, 'keywords')) {
                $prompt .= "The website keywords are: {$pageKeywords}.";
            }

            $prompt .= "At the end of the feedback create a 10-20 bullet point list from the suggested imporvements. Explain the top 5 most required imporvements and propose concrete solutions for each with links and specific informations. \n\n";

            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            $roast = $result['choices'][0]['text'];

            $this->payment->update([
                'roast' => $roast,
            ]);

            Log::info("Roast is ready for payment {$this->payment->id}.");
        } catch (Throwable $th) {
            if ($this->attempts() > $this->tires - 1) {
                Log::error("Roast creation failed for payment {$this->payment->id}.");
                throw $th;
            }

            $this->release($this->timeout);

            return;
        }
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
