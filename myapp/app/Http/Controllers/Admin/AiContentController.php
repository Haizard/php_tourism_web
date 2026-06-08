<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\BlogCategory;
use App\Services\GeminiContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiContentController extends Controller
{
    private GeminiContentService $geminiService;

    public function __construct(GeminiContentService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function generateTourContent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'destination_id' => 'nullable|integer|exists:destinations,id',
            'duration' => 'nullable|integer|min:1',
            'itinerary' => 'nullable|array',
            'itinerary.*.title' => 'nullable|string|max:255',
            'itinerary.*.description' => 'nullable|string',
        ]);

        if (!$this->geminiService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.',
            ], 400);
        }

        try {
            $destination = null;
            if (!empty($validated['destination_id'])) {
                $destination = Destination::find($validated['destination_id']);
                $destinationName = $destination->name ?? null;
            } else {
                $destinationName = null;
            }

            $itinerary = $validated['itinerary'] ?? [];
            $duration = $validated['duration'] ?? null;

            $generatedContent = $this->geminiService->generateCompleteTour(
                $validated['title'],
                $itinerary,
                $destinationName,
                $duration
            );

            return response()->json([
                'success' => true,
                'data' => $generatedContent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateTourSeo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'destination_id' => 'nullable|integer|exists:destinations,id',
        ]);

        if (!$this->geminiService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured.',
            ], 400);
        }

        try {
            $destination = null;
            if (!empty($validated['destination_id'])) {
                $destination = Destination::find($validated['destination_id']);
                $destinationName = $destination->name ?? null;
            } else {
                $destinationName = null;
            }

            $content = $validated['content'] ?? '';
            
            $seoData = $this->geminiService->generateTourSeo(
                $validated['title'],
                $content,
                $destinationName
            );

            return response()->json([
                'success' => true,
                'data' => $seoData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate SEO data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateBlogContent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string|max:100',
        ]);

        if (!$this->geminiService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured.',
            ], 400);
        }

        try {
            $category = null;
            if (!empty($validated['category_id'])) {
                $category = BlogCategory::find($validated['category_id']);
                $categoryName = $category->name ?? null;
            } else {
                $categoryName = null;
            }

            $keywords = $validated['keywords'] ?? [];

            $generatedContent = $this->geminiService->generateCompleteBlog(
                $validated['title'],
                $categoryName,
                $keywords
            );

            return response()->json([
                'success' => true,
                'data' => $generatedContent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateBlogSeo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
        ]);

        if (!$this->geminiService->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured.',
            ], 400);
        }

        try {
            $category = null;
            if (!empty($validated['category_id'])) {
                $category = BlogCategory::find($validated['category_id']);
                $categoryName = $category->name ?? null;
            } else {
                $categoryName = null;
            }

            $content = $validated['content'] ?? '';
            
            $seoData = $this->geminiService->generateBlogSeo(
                $validated['title'],
                $content,
                $categoryName
            );

            return response()->json([
                'success' => true,
                'data' => $seoData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate SEO data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkAvailability(): JsonResponse
    {
        return response()->json([
            'available' => $this->geminiService->isAvailable(),
        ]);
    }
}