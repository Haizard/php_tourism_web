<?php

namespace App\Livewire;

use App\Models\Destination;
use App\Models\BlogCategory;
use App\Services\GeminiContentService;
use Livewire\Component;

class AiContentGenerator extends Component
{
    public string $type = 'tour';
    public bool $generating = false;
    public bool $aiAvailable = false;
    public ?string $message = null;
    public string $messageType = 'success';

    public function mount()
    {
        $this->checkAvailability();
    }

    public function checkAvailability()
    {
        $service = app(GeminiContentService::class);
        $this->aiAvailable = $service->isAvailable();
    }

    public function generateContent(array $data)
    {
        if (!$this->aiAvailable) {
            $this->message = 'Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.';
            $this->messageType = 'error';
            return null;
        }

        $this->generating = true;
        $this->message = null;

        try {
            $service = app(GeminiContentService::class);
            $result = null;

            if ($this->type === 'tour') {
                $title = $data['title'] ?? '';
                if (empty($title)) {
                    throw new \Exception('Please enter a tour title before generating content.');
                }

                $destinationId = $data['destination_id'] ?? null;
                $duration = $data['duration'] ?? null;
                $itinerary = $data['itinerary'] ?? [];
                
                $destinationName = null;
                if ($destinationId) {
                    $destination = Destination::find($destinationId);
                    $destinationName = $destination->name ?? null;
                }

                $durationNumber = null;
                if ($duration) {
                    preg_match('/(\d+)/', $duration, $matches);
                    $durationNumber = isset($matches[1]) ? (int)$matches[1] : null;
                }

                $itineraryArray = [];
                if (!empty($itinerary) && is_array($itinerary)) {
                    foreach ($itinerary as $key => $value) {
                        $itineraryArray[] = [
                            'title' => $key,
                            'description' => $value,
                        ];
                    }
                }

                $result = $service->generateCompleteTour(
                    $title,
                    $itineraryArray,
                    $destinationName,
                    $durationNumber
                );
            } else {
                $title = $data['title'] ?? '';
                if (empty($title)) {
                    throw new \Exception('Please enter a blog title before generating content.');
                }

                $categoryId = $data['category_id'] ?? null;
                $categoryName = null;
                if ($categoryId) {
                    $category = BlogCategory::find($categoryId);
                    $categoryName = $category->name ?? null;
                }

                $result = $service->generateCompleteBlog(
                    $title,
                    $categoryName
                );
            }

            $this->message = 'Content generated successfully!';
            $this->messageType = 'success';
            
            return $result;
        } catch (\Exception $e) {
            $this->message = 'Error: ' . $e->getMessage();
            $this->messageType = 'error';
            return null;
        } finally {
            $this->generating = false;
        }
    }

    public function render()
    {
        return view('livewire.ai-content-generator');
    }
}