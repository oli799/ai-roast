<?php

namespace App\Jobs;

use App\Enums\DimensionEnum;
use App\Http\Requests\CreateRoast as CreateRoastRequest;
use App\Http\Requests\CreateScreenshot;
use App\Models\Payment;
use App\Models\User;
use App\Spiders\UrlSpider;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;
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
            $content = $this->getWebsiteContent($this->payment->url);
            $prompt = $this->getPrompt($this->payment->url, $content);
            $images = $this->getImagesArray();

            $roastRequest = new CreateRoastRequest(config('openai.api_key'), $prompt, $images);
            $roastResponse = $roastRequest->send();

            $this->payment->update([
                'roast' => $roastResponse->json('choices.0.message.content'),
                'parsed_at' => now(),
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

    private function getPrompt(string $url, ?string $content): string
    {
        $prompt = 'You are a designer, marketing and seo expert who revivews the following website:'.PHP_EOL;
        $prompt .= "The website url is: {$url}.".PHP_EOL;

        if ($content !== '' && $content !== '0') {
            $prompt .= "The website content is: {$content}.".PHP_EOL;
        } else {
            $prompt .= 'The website content is missing.';
        }

        if ($pageDescription = $this->getMetaTagValue($url, 'description')) {
            $prompt .= "The website meta description is: {$pageDescription}.".PHP_EOL;
        } else {
            $prompt .= 'The website meta description is missing.';
        }

        if ($pageKeywords = $this->getMetaTagValue($url, 'keywords')) {
            $prompt .= "The website meta keywords are: {$pageKeywords}.".PHP_EOL;
        } else {
            $prompt .= 'The website meta keywords are missing.';
        }

        $prompt .= 'The first photo is a screenshot of the website on a phone and the second is a screenshot of the website on a computer screen.'.PHP_EOL;

        $prompt .= 'First, please create a one sentence first impression of the website.'.PHP_EOL;
        $prompt .= 'After the first impresion please, create a 1 sentence feedback and 1 senence advice, if no advice needed please respond "No changes recommended". In temrs of advice, you can use links and specific examples.'.PHP_EOL;
        $prompt .= 'In each feedback topick you can use the subtopic_description key in the response format to get and idea of what the current subtopic is about.'.PHP_EOL;
        $prompt .= 'After the feedback please write a final sentence with a conclusion.'.PHP_EOL;

        $responseFormat = [
            'first_impression' => '',
            'topics' => [
                [
                    'topic_name' => 'user_interface',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Check the navigation menu for clarity and ease of use. Ensure that users can easily find what they\'re looking for.',
                            'subtopic_name' => 'navigation',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Look for consistency in design elements such as colors, fonts, and buttons across different pages.',
                            'subtopic_name' => 'consistency',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Ensure that content and layout adapt appropriately to different screen sizes, providing an optimal user experience regardless of the device being used.',
                            'subtopic_name' => 'responsiveness',
                            'feedback' => '',
                        ],
                    ],
                    'advice' => '',
                ],
                [
                    'topic_name' => 'user_experience',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Evaluate the overall layout for readability and logical flow. Ensure that important information is prominently displayed.',
                            'subtopic_name' => 'page_layout',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Check the visibility and effectiveness of call-to-action buttons. They should be clear and encourage user interaction.',
                            'subtopic_name' => 'cta',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => ' If exists, review forms for simplicity and user-friendliness. Ensure that error messages are helpful.',
                            'subtopic_name' => 'forms',
                            'feedback' => '',
                        ],
                    ],
                    'advice' => '',
                ],
                [
                    'topic_name' => 'visual_design',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Assess the color scheme for harmony and readability. Consider the psychological impact of colors on users.',
                            'subtopic_name' => 'color_scheme',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Check font styles, sizes, and spacing for readability. Ensure that the text is legible on different devices.',
                            'subtopic_name' => 'typography',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Evaluate the quality and relevance of images and multimedia elements. Make sure they enhance the overall design.',
                            'subtopic_name' => 'images_and_multimedia',
                            'feedback' => '',
                        ],
                    ],
                    'advice' => '',
                ],
                [
                    'topic_name' => 'content',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Review the clarity and conciseness of the content. Ensure that information is presented in a straightforward manner.',
                            'subtopic_name' => 'clarity',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Check if the content aligns with the target audience and the website\'s purpose.',
                            'subtopic_name' => 'relevance',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Assess the use of headings, subheadings, and paragraphs to improve content readability.',
                            'subtopic_name' => 'readability',
                            'feedback' => '',
                        ],
                    ],
                    'advice' => '',
                ],
                [
                    'topic_name' => 'Readability',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Assess the perceived trustworthiness of the landing page. This could include factors like security icons, testimonials, or other trust indicators.',
                            'subtopic_name' => 'trustworthiness',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Inquire about the impact of social proof elements (such as reviews, testimonials, or social media endorsements) on the user\'s perception.',
                            'subtopic_name' => 'social_proof',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Find out if users are encountering any issues that prompt them to leave the page without converting.',
                            'subtopic_name' => 'exit_points',
                            'feedback' => '',
                        ],
                    ],
                ],
                [
                    'topic_name' => 'seo',
                    'subtopics' => [
                        [
                            'subtopic_description' => 'Check for appropriate meta titles and descriptions.',
                            'subtopic_name' => 'meta_description',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Check for appropriate meta keywords.',
                            'subtopic_name' => 'meta_keywords',
                            'feedback' => '',
                        ],
                        [
                            'subtopic_description' => 'Ensure that URLs are SEO-friendly and provide a clear hierarchy.',
                            'subtopic_name' => 'url_structure',
                            'feedback' => '',
                        ],
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

        if ($rawPhoneImage = file_get_contents($phoneImageUrl)) {
            Storage::disk('public')->put("images/{$this->payment->id}/phone.png", $rawPhoneImage);

            $this->payment->update([
                'phone_image_url' => Storage::disk('public')->url("images/{$this->payment->id}/phone.png"),
            ]);
        } else {
            Log::error("Failed to get open {$phoneImageUrl}.");

            $this->payment->update([
                'phone_image_url' => $phoneImageUrl,
            ]);
        }

        $images[] = [
            'type' => 'image_url',
            'image_url' => [
                'url' => $phoneImageUrl,
            ],
        ];

        $computerImageUrl = $this->getScreenshotUrl($this->payment->url, DimensionEnum::COMPUTER);

        if ($rawComputerImage = file_get_contents($computerImageUrl)) {
            Storage::disk('public')->put("images/{$this->payment->id}/computer.png", $rawComputerImage);

            $this->payment->update([
                'computer_image_url' => Storage::disk('public')->url("images/{$this->payment->id}/computer.png"),
            ]);
        } else {
            Log::error("Failed to get open {$computerImageUrl}.");

            $this->payment->update([
                'computer_image_url' => $computerImageUrl,
            ]);
        }

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

    private function getWebsiteContent(string $url): ?string
    {
        try {
            $html = Roach::collectSpider(UrlSpider::class, new Overrides(startUrls: [$url]))[0]->get('content');
        } catch (Exception) {
            Log::error("Failed to get open {$url}.");

            return null;
        }

        $content = [];

        $elementsToParse = [
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'p',
            'a',
            'img',
            'ul',
            'ol',
            'table',
            'span',
            'button',
            'input',
            'strong',
            'em',
            'i',
            'b',
            'u',
            's',
            'strike',
            'del',
            'code',
            'pre',
            'blockquote',
            'q',
            'abbr',
            'address',
            'cite',
            'small',
            'li',
            'marquee',
            'center',
            'td',
            'tr',
            'option',
        ];

        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        foreach ($elementsToParse as $element) {
            $elements = $dom->getElementsByTagName($element);
            $this->append_tag_values($elements, $content);
        }

        if ((is_countable($content) ? count($content) : 0) <= 0) {
            return null;
        }

        return implode("\n", array_unique($content));
    }

    /**
     * @param  DOMNodeList<DOMElement>  $tag
     * @param  array<string>  $content
     */
    private function append_tag_values(DOMNodeList $tag, array &$content): void
    {
        if (! $tag->length > 0) {
            return;
        }

        foreach ($tag as $t) {
            if (! $t->nodeValue) {
                continue;
            }

            if (! $replaced = preg_replace('/\s\s+/', ' ', (string) $t->nodeValue)) {
                continue;
            }

            $content[] = trim($replaced);
        }
    }
}
