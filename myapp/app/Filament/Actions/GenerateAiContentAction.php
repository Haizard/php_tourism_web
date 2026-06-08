<?php

namespace App\Filament\Actions;

use App\Services\GeminiContentService;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Log;

class GenerateAiContentAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('warning')
            ->icon('heroicon-o-sparkles')
            ->requiresConfirmation()
            ->modalHeading('Generate Content with AI')
            ->modalDescription('AI will generate content based on the title and other details you\'ve entered. This may take a few moments.')
            ->modalSubmitActionLabel('Generate')
            ->modalCancelActionLabel('Cancel');
    }

    public static function makeForTour(): static
    {
        return static::make('generate_ai_content')
            ->label('✨ Generate Content with AI')
            ->action(function (array $data, GeminiContentService $geminiService) {
                if (!$geminiService->isAvailable()) {
                    throw new \Exception('Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.');
                }

                try {
                    $title = $data['title'] ?? '';
                    if (empty($title)) {
                        throw new \Exception('Please enter a tour title before generating content.');
                    }

                    $destinationId = $data['destination_id'] ?? null;
                    $duration = $data['duration'] ?? null;
                    $itinerary = $data['itinerary'] ?? [];
                    
                    // Extract destination name if ID provided
                    $destinationName = null;
                    if ($destinationId) {
                        $destination = \App\Models\Destination::find($destinationId);
                        $destinationName = $destination->name ?? null;
                    }

                    // Parse duration to extract number
                    $durationNumber = null;
                    if ($duration) {
                        preg_match('/(\d+)/', $duration, $matches);
                        $durationNumber = isset($matches[1]) ? (int)$matches[1] : null;
                    }

                    // Convert itinerary from key-value format
                    $itineraryArray = [];
                    if (!empty($itinerary) && is_array($itinerary)) {
                        foreach ($itinerary as $key => $value) {
                            $itineraryArray[] = [
                                'title' => $key,
                                'description' => $value,
                            ];
                        }
                    }

                    $generatedContent = $geminiService->generateCompleteTour(
                        $title,
                        $itineraryArray,
                        $destinationName,
                        $durationNumber
                    );

                    return $generatedContent;
                } catch (\Exception $e) {
                    Log::error('AI Content Generation Error: ' . $e->getMessage());
                    throw $e;
                }
            });
    }

    public static function makeForBlog(): static
    {
        return static::make('generate_ai_content')
            ->label('✨ Generate Content with AI')
            ->action(function (array $data, GeminiContentService $geminiService) {
                if (!$geminiService->isAvailable()) {
                    throw new \Exception('Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.');
                }

                try {
                    $title = $data['title'] ?? '';
                    if (empty($title)) {
                        throw new \Exception('Please enter a blog title before generating content.');
                    }

                    $categoryId = $data['category_id'] ?? null;
                    $categoryName = null;
                    if ($categoryId) {
                        $category = \App\Models\BlogCategory::find($categoryId);
                        $categoryName = $category->name ?? null;
                    }

                    $generatedContent = $geminiService->generateCompleteBlog(
                        $title,
                        $categoryName
                    );

                    return $generatedContent;
                } catch (\Exception $e) {
                    Log::error('AI Content Generation Error: ' . $e->getMessage());
                    throw $e;
                }
            });
    }
}