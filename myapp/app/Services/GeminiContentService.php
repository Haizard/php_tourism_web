<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiContentService
{
    private ?string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
        // Use gemini-2.0-flash for better quality, but it has stricter rate limits
        // If you hit rate limits, change to 'gemini-1.5-flash' which has higher free tier limits
        $this->model = env('GEMINI_MODEL', 'gemini-2.0-flash');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function generateTourContent(string $title, array $itinerary = [], ?string $destination = null, ?int $duration = null): array
    {
        if (!$this->isAvailable()) {
            return $this->getFallbackTourContent($title);
        }

        $itineraryText = $this->formatItineraryForPrompt($itinerary);
        
        $prompt = $this->buildTourContentPrompt($title, $itineraryText, $destination, $duration);
        
        try {
            $response = $this->makeRequest($prompt);
            
            if ($response) {
                return $this->parseTourContentResponse($response);
            }
        } catch (Exception $e) {
            Log::warning('Gemini tour content generation failed: ' . $e->getMessage());
        }

        return $this->getFallbackTourContent($title);
    }

    public function generateTourSeo(string $title, string $content = '', ?string $destination = null): array
    {
        if (!$this->isAvailable()) {
            return $this->getFallbackSeo($title);
        }

        $contentSnippet = substr($content, 0, 500);
        
        $prompt = "Generate SEO-optimized meta data for a tour titled \"$title\" in $destination.\n\n".
            "Content preview: $contentSnippet\n\n".
            "Provide:\n".
            "1. SEO meta title (50-60 characters, include main keywords)\n".
            "2. SEO meta description (150-160 characters, compelling and keyword-rich)\n".
            "3. SEO keywords (5-8 relevant keywords, comma-separated)\n\n".
            "Format the response as JSON with keys: meta_title, meta_description, keywords";
        
        try {
            $response = $this->makeRequest($prompt);
            
            if ($response) {
                return $this->parseSeoResponse($response);
            }
        } catch (Exception $e) {
            Log::warning('Gemini SEO generation failed: ' . $e->getMessage());
        }

        return $this->getFallbackSeo($title);
    }

    public function generateBlogContent(string $title, ?string $category = null, array $keywords = []): array
    {
        if (!$this->isAvailable()) {
            return $this->getFallbackBlogContent($title);
        }

        $keywordsText = !empty($keywords) ? 'Include these keywords: ' . implode(', ', $keywords) : '';
        
        $prompt = $this->buildBlogContentPrompt($title, $category, $keywordsText);
        
        try {
            $response = $this->makeRequest($prompt);
            
            if ($response) {
                return $this->parseBlogContentResponse($response);
            }
        } catch (Exception $e) {
            Log::warning('Gemini blog content generation failed: ' . $e->getMessage());
        }

        return $this->getFallbackBlogContent($title);
    }

    public function generateBlogSeo(string $title, string $content = '', ?string $category = null): array
    {
        if (!$this->isAvailable()) {
            return $this->getFallbackSeo($title);
        }

        $contentSnippet = substr($content, 0, 500);
        $categoryText = $category ? " in the $category category" : '';
        
        $prompt = "Generate SEO-optimized meta data for a blog post titled \"$title\"$categoryText.\n\n".
            "Content preview: $contentSnippet\n\n".
            "Provide:\n".
            "1. SEO meta title (50-60 characters, include main keywords)\n".
            "2. SEO meta description (150-160 characters, compelling and keyword-rich)\n".
            "3. SEO keywords (5-8 relevant keywords, comma-separated)\n\n".
            "Format the response as JSON with keys: meta_title, meta_description, keywords";
        
        try {
            $response = $this->makeRequest($prompt);
            
            if ($response) {
                return $this->parseSeoResponse($response);
            }
        } catch (Exception $e) {
            Log::warning('Gemini SEO generation failed: ' . $e->getMessage());
        }

        return $this->getFallbackSeo($title);
    }

    public function generateCompleteTour(string $title, array $itinerary = [], ?string $destination = null, ?int $duration = null): array
    {
        // If no itinerary provided, generate one
        if (empty($itinerary) && $duration) {
            $itinerary = $this->generateItinerary($title, $destination, $duration);
        }
        
        $content = $this->generateTourContent($title, $itinerary, $destination, $duration);
        $seo = $this->generateTourSeo($title, $content['content'] ?? '', $destination);
        
        // Include itinerary in response
        $content['itinerary'] = $itinerary;
        
        return array_merge($content, $seo);
    }

    public function generateCompleteBlog(string $title, ?string $category = null, array $keywords = []): array
    {
        $content = $this->generateBlogContent($title, $category, $keywords);
        $seo = $this->generateBlogSeo($title, $content['content'] ?? '', $category);
        
        return array_merge($content, $seo);
    }

    private function makeRequest(string $prompt): ?string
    {
        $response = Http::timeout(30)->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
            ],
        ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
        return null;
    }

    private function buildTourContentPrompt(string $title, string $itineraryText, ?string $destination, ?int $duration): string
    {
        $durationText = $duration ? "$duration days" : "multiple days";
        $destinationText = $destination ? "in $destination" : "";
        
        $prompt = "You are an expert tour guide and professional travel content writer with 15+ years of experience in adventure tourism and SEO-optimized content creation. Your expertise includes mountain climbing, safari tours, cultural experiences, and creating compelling travel narratives that convert readers into bookings.\n\n".
            "Create premium, SEO-optimized content for this tour package:\n\n".
            "**Tour Details:**\n".
            "Title: $title\n".
            "Destination: $destinationText\n".
            "Duration: $durationText\n\n".
            "**Itinerary Overview:**\n$itineraryText\n\n".
            "**SEO Keywords to Include:** " . $this->generateSeoKeywords($title, $destination) . "\n\n".
            "Generate the following content in JSON format:\n".
            '{'."\n".
            '    "excerpt": "A compelling 2-3 sentence hook (150-200 characters) that creates urgency and highlights unique selling points. Include primary keywords naturally.",'."\n".
            '    "content": "A comprehensive 600-800 word description that includes:'."\n".
            '        - Engaging opening paragraph that paints a vivid picture'."\n".
            '        - What makes this tour unique and unforgettable'."\n".
            '        - Detailed description of key experiences and activities'."\n".
            '        - Information about difficulty level, best time to go, and what to expect'."\n".
            '        - Safety measures and professional guidance'."\n".
            '        - Emotional appeal that connects with adventure seekers'."\n".
            '        - Natural integration of SEO keywords throughout'."\n".
            '        - Strong call-to-action closing paragraph",'."\n".
            '    "highlights": ['."\n".
            '        "Unique experience or once-in-a-lifetime opportunity",'."\n".
            '        "Professional certified guides with local expertise",'."\n".
            '        "Small group sizes for personalized attention",'."\n".
            '        "Premium equipment and safety measures",'."\n".
            '        "Stunning landscapes and photo opportunities",'."\n".
            '        "Cultural immersion and local interactions",'."\n".
            '        "All-inclusive package with no hidden costs"'."\n".
            '    ],'."\n".
            '    "included_services": ['."\n".
            '        "Professional licensed tour guide with first aid certification",'."\n".
            '        "All ground transportation in comfortable 4WD vehicles",'."\n".
            '        "Accommodation as specified in the itinerary",'."\n".
            '        "All meals during the tour (breakfast, lunch, dinner)",'."\n".
            '        "Park entrance fees and permits",'."\n".
            '        "Porter service and equipment transport",'."\n".
            '        "Emergency oxygen and first aid kits",'."\n".
            '        "Pre-tour briefing and equipment check"'."\n".
            '    ],'."\n".
            '    "excluded_services": ['."\n".
            '        "International flights and visa fees",'."\n".
            '        "Travel and medical insurance (mandatory)",'."\n".
            '        "Personal gear and equipment rental",'."\n".
            '        "Tips and gratuities for guides and porters (recommended $150-200)",'."\n".
            '        "Personal expenses and souvenirs",'."\n".
            '        "Optional activities not mentioned in itinerary",'."\n".
            '        "Alcoholic beverages and soft drinks"'."\n".
            '    ]'."\n".
            '}'."\n\n".
            "**Writing Guidelines:**\n".
            "- Use vivid, sensory language that transports readers to the destination\n".
            "- Include emotional triggers: adventure, achievement, discovery, transformation\n".
            "- Address common concerns: safety, fitness requirements, value for money\n".
            "- Optimize for search engines while maintaining natural readability\n".
            "- Write in active voice with strong, compelling verbs\n".
            "- Include specific details that build trust and credibility\n".
            "- Create urgency with limited availability or seasonal considerations\n\n".
            "Respond ONLY with the JSON object, no additional text.";
        
        return $prompt;
    }

    private function buildBlogContentPrompt(string $title, ?string $category, string $keywordsText): string
    {
        $categoryText = $category ? "Category: $category\n" : "";
        
        $prompt = "You are a professional travel blogger. Generate a comprehensive blog post.\n\n".
            "Blog Title: $title\n".
            $categoryText.
            $keywordsText."\n\n".
            "Generate the following content in JSON format:\n".
            '{'."\n".
            '    "excerpt": "A compelling 2-3 sentence summary (150-200 characters)",'."\n".
            '    "content": "Full blog article (500-800 words) with HTML headings (h2, h3) and paragraphs",'."\n".
            '    "highlights": ["Key point 1", "Key point 2", "Key point 3"]'."\n".
            '}'."\n\n".
            "Make the content engaging, SEO-optimized, and well-structured. Respond ONLY with the JSON object.";
        
        return $prompt;
    }

    private function formatItineraryForPrompt(array $itinerary): string
    {
        if (empty($itinerary)) {
            return "No detailed itinerary provided. Create a compelling itinerary based on the tour title and destination.";
        }

        $formatted = "";
        $dayCounter = 1;
        foreach ($itinerary as $index => $day) {
            $dayTitle = $day['title'] ?? "Day " . $dayCounter;
            $description = $day['description'] ?? '';
            
            $formatted .= "Day " . $dayCounter . ": " . $dayTitle . "\n";
            if ($description) {
                $formatted .= $description . "\n\n";
            }
            $dayCounter++;
        }

        return trim($formatted);
    }

    private function parseTourContentResponse(string $response): array
    {
        $data = $this->extractJson($response);
        
        return [
            'excerpt' => $data['excerpt'] ?? $this->generateFallbackExcerpt($data['content'] ?? ''),
            'content' => $data['content'] ?? 'Content generation failed. Please add content manually.',
            'highlights' => $data['highlights'] ?? [],
            'included_services' => $data['included_services'] ?? [],
            'excluded_services' => $data['excluded_services'] ?? [],
        ];
    }

    private function parseBlogContentResponse(string $response): array
    {
        $data = $this->extractJson($response);
        
        return [
            'excerpt' => $data['excerpt'] ?? $this->generateFallbackExcerpt($data['content'] ?? ''),
            'content' => $data['content'] ?? 'Content generation failed. Please add content manually.',
            'highlights' => $data['highlights'] ?? [],
        ];
    }

    private function parseSeoResponse(string $response): array
    {
        $data = $this->extractJson($response);
        
        return [
            'seo_meta_title' => $data['meta_title'] ?? '',
            'seo_meta_description' => $data['meta_description'] ?? '',
            'seo_keywords' => $data['keywords'] ?? '',
        ];
    }

    private function extractJson(string $text): array
    {
        $pattern = '/\{[\s\S]*\}/';
        if (preg_match($pattern, $text, $matches)) {
            $json = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
        }
        
        return [];
    }

    private function getFallbackTourContent(string $title): array
    {
        return [
            'excerpt' => "Experience the amazing $title tour. Book now for an unforgettable adventure!",
            'content' => "Join us for the incredible $title tour. This amazing experience will create lasting memories. Contact us for more details and to book your spot today!",
            'highlights' => ["Professional guide", "Unique experience", "Great value"],
            'included_services' => ["Professional tour guide", "Transportation"],
            'excluded_services' => ["Personal expenses", "Travel insurance"],
        ];
    }

    private function getFallbackBlogContent(string $title): array
    {
        return [
            'excerpt' => "Discover everything you need to know about $title in this comprehensive guide.",
            'content' => "<h2>Introduction</h2><p>Welcome to our guide on $title. This article will provide you with valuable insights and information.</p><h2>Key Points</h2><p>There are many aspects to consider when exploring $title. Let's dive into the details.</p><h2>Conclusion</h2><p>We hope this guide helps you understand $title better. Contact us for more information or to book your next adventure!</p>",
            'highlights' => ["Comprehensive guide", "Expert insights", "Practical tips"],
        ];
    }

    private function getFallbackSeo(string $title): array
    {
        return [
            'seo_meta_title' => substr($title, 0, 60),
            'seo_meta_description' => substr("Learn more about $title. Book your experience today!", 0, 160),
            'seo_keywords' => strtolower(str_replace(' ', ', ', $title)),
        ];
    }

    private function generateFallbackExcerpt(string $content): string
    {
        return substr(strip_tags($content), 0, 200) . '...';
    }

    public function generateItinerary(string $title, ?string $destination = null, ?int $duration = null): array
    {
        if (!$this->isAvailable() || !$duration) {
            return $this->getFallbackItinerary($duration ?? 1);
        }

        $destinationText = $destination ? "in $destination" : "";
        
        $prompt = "You are an expert tour guide and itinerary planner with 20+ years of experience creating unforgettable adventure tours. Your specialty is mountain climbing, safari expeditions, and cultural immersion tours.\n\n".
            "Create a detailed, day-by-day itinerary for: \"$title\" $destinationText ($duration days)\n\n".
            "**Itinerary Requirements:**\n".
            "- Each day must have a specific, engaging title that captures the day's highlight\n".
            "- Each description must be 3-4 detailed sentences covering:\n".
            "  * Morning activities and schedule\n".
            "  * Afternoon adventures and experiences\n".
            "  * Evening arrangements and accommodations\n".
            "  * Specific locations, landmarks, or points of interest\n".
            "  * Physical difficulty level and what to expect\n".
            "  * Unique experiences or photo opportunities\n\n".
            "**Tour Guide Expertise to Include:**\n".
            "- Safety briefings and equipment checks\n".
            "- Acclimatization schedules (for high-altitude tours)\n".
            "- Meal arrangements and dietary considerations\n".
            "- Cultural interactions with local communities\n".
            "- Wildlife spotting opportunities (if applicable)\n".
            "- Weather considerations and backup plans\n\n".
            "Return a JSON array with exactly $duration days. Each day object must have:\n".
            '{'."\n".
            '    "title": "Catchy day title (e.g., Summit Day: Conquering the Peak)",'."\n".
            '    "description": "Detailed 3-4 sentence description covering all activities, locations, and experiences"'."\n".
            '}';
        
        try {
            $response = $this->makeRequest($prompt);
            
            if ($response) {
                $data = json_decode($response, true);
                if (is_array($data) && !empty($data)) {
                    // Ensure we have exactly $duration days
                    return array_slice($data, 0, $duration);
                }
            }
        } catch (Exception $e) {
            Log::warning('Gemini itinerary generation failed: ' . $e->getMessage());
        }

        return $this->getFallbackItinerary($duration ?? 1);
    }

    private function getFallbackItinerary(int $duration): array
    {
        $itinerary = [];
        for ($i = 1; $i <= $duration; $i++) {
            $itinerary["Day " . $i] = "Day " . $i . " activities and exploration";
        }
        return $itinerary;
    }

    private function generateSeoKeywords(string $title, ?string $destination): string
    {
        $keywords = [];
        
        // Extract key terms from title
        $titleWords = str_word_count(strtolower($title), 1);
        $keywords = array_merge($keywords, array_slice($titleWords, 0, 5));
        
        // Add destination-related keywords
        if ($destination) {
            $keywords[] = strtolower($destination);
            $keywords[] = "tour " . strtolower($destination);
            $keywords[] = strtolower($destination) . " adventure";
        }
        
        // Add general tour keywords
        $keywords = array_merge($keywords, [
            'book now',
            'best price',
            'guided tour',
            'adventure tour',
            'tour package',
            'vacation',
            'travel',
            'holiday'
        ]);
        
        return implode(', ', array_unique($keywords));
    }
}
