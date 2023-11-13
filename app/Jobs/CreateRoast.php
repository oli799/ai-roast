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
        $prompt = 'You are a designer, marketing and seo expert who revivews the following website:'.PHP_EOL;
        $prompt .= "The website url is: {$url}.".PHP_EOL;

        if ($pageDescription = $this->getMetaTagValue($url, 'description')) {
            $prompt .= "The website meta description is: {$pageDescription}.".PHP_EOL;
        }

        if ($pageKeywords = $this->getMetaTagValue($url, 'keywords')) {
            $prompt .= "The website meta keywords are: {$pageKeywords}.".PHP_EOL;
        }

        $prompt .= 'The first photo is a screenshot of the website on a phone and the second is a screenshot of the website on a computer.'.PHP_EOL;

        $prompt .= 'First, please create a one sentence first impression of the website.'.PHP_EOL;
        $prompt .= 'After the first impresion please, create a 1 sentence feedback and 1 senence advice. In temrs of advice, you can use links and specific examples.'.PHP_EOL;
        $prompt .= 'In each feedback topick you can use the topic_description key in the response format to get and idea of what the current topic is about.'.PHP_EOL;
        $prompt .= 'After the feedback please write a final sentence with a conclusion.'.PHP_EOL;

        $responseFormat = [
            'first_impression' => '',
            'topics' => [
                'user_interface' => [
                    'navigation' => [
                        'topic_description' => 'Check the navigation menu for clarity and ease of use. Ensure that users can easily find what they\'re looking for.',
                        'feedback' => '',
                    ],
                    'consistency' => [
                        'topic_description' => 'Look for consistency in design elements such as colors, fonts, and buttons across different pages.',
                        'feedback' => '',
                    ],
                    'responsiveness' => [
                        'topic_description' => 'Verify that the design is responsive and adapts well to different screen sizes, including mobile devices and tablets.',
                        'feedback' => '',
                    ],
                    'advice' => '',
                ],
                'user_experience' => [
                    'page_layout' => [
                        'topic_description' => 'Evaluate the overall layout for readability and logical flow. Ensure that important information is prominently displayed.',
                        'feedback' => '',
                    ],
                    'cta' => [
                        'topic_description' => 'Check the visibility and effectiveness of call-to-action buttons. They should be clear and encourage user interaction.',
                        'feedback' => '',
                    ],
                    'forms' => [
                        'topic_description' => ' If exists, review forms for simplicity and user-friendliness. Ensure that error messages are helpful.',
                        'feedback' => '',
                    ],
                    'advice' => '',
                ],
                'visual_desing' => [
                    'color_scheme' => [
                        'topic_description' => 'Assess the color scheme for harmony and readability. Consider the psychological impact of colors on users.',
                        'feedback' => '',
                    ],
                    'typography' => [
                        'topic_description' => 'Check font styles, sizes, and spacing for readability. Ensure that the text is legible on different devices.',
                        'feedback' => '',
                    ],
                    'images_and_multimedia' => [
                        'topic_description' => 'Evaluate the quality and relevance of images and multimedia elements. Make sure they enhance the overall design.',
                        'feedback' => '',
                    ],
                    'advice' => '',
                ],
                'content' => [
                    'clarity' => [
                        'topic_description' => 'Review the clarity and conciseness of the content. Ensure that information is presented in a straightforward manner.',
                        'feedback' => '',
                    ],
                    'relevance' => [
                        'topic_description' => 'Check if the content aligns with the target audience and the website\'s purpose.',
                        'feedback' => '',
                    ],
                    'readability' => [
                        'topic_description' => 'Assess the use of headings, subheadings, and paragraphs to improve content readability.',
                        'feedback' => '',
                    ],
                    'advice' => '',
                ],
                'seo' => [
                    'meta_description' => [
                        'topic_description' => 'Check for appropriate meta titles and descriptions.',
                        'feedback' => '',
                    ],
                    'meta_keywords' => [
                        'topic_description' => 'Check for appropriate meta keywords.',
                        'feedback' => '',
                    ],
                    'url_structure' => [
                        'topic_description' => 'Ensure that URLs are SEO-friendly and provide a clear hierarchy.',
                        'feedback' => '',
                    ],
                    'advice' => '',
                ],
            ],
            'final_thoughts' => '',
        ];

        return $prompt.('Response format: '.json_encode($responseFormat).PHP_EOL);
    }

    /**
     * @return array<int,array<string,array<string,string>|string>>
     */
    private function getImagesArray(): array
    {
        $images = [];
        $phoneImageUrl = $this->getScreenshotUrl($this->payment->url, DimensionEnum::PHONE);

        $this->payment->update([
            'phone_image_url' => $phoneImageUrl,
        ]);

        $images[] = [
            'type' => 'image_url',
            'image_url' => [
                'url' => $phoneImageUrl,
            ],
        ];

        $computerImageUrl = $this->getScreenshotUrl($this->payment->url, DimensionEnum::COMPUTER);

        $this->payment->update([
            'computer_image_url' => $computerImageUrl,
        ]);

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
