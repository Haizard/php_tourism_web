<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Destination;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private GeneralSettings $generalSettings;
    private MailSettings $mailSettings;

    public function __construct(GeneralSettings $generalSettings, MailSettings $mailSettings)
    {
        $this->generalSettings = $generalSettings;
        $this->mailSettings    = $mailSettings;
    }

    public function chat(Request $request): JsonResponse
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userMessage = trim($request->input('message'));

        $reply = $this->tryGemini($userMessage)
            ?? $this->buildReply(strtolower($userMessage));

        return response()->json([
            'reply' => $reply['text'],
            'type'  => $reply['type'] ?? 'text',
            'data'  => $reply['data'] ?? null,
        ]);
    }

    private function tryGemini(string $message): ?array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return null;
        }

        try {
            $siteName = $this->generalSettings->siteName;
            $email    = $this->generalSettings->contactEmail;
            $phone    = $this->generalSettings->contactPhone;

            $tours = Tour::where('is_published', true)->with('destination')->take(10)->get();
            $tourList = $tours->map(fn($t) =>
                "- {$t->title}" .
                ($t->duration ? " ({$t->duration})" : '') .
                ($t->price ? ' from $' . number_format($t->discount_price ?? $t->price, 0) : '') .
                ($t->destination ? " in {$t->destination->name}" : '')
            )->join("\n");

            $destinations = Destination::take(10)->get()->pluck('name')->join(', ');

            $systemPrompt = "You are a friendly travel assistant for {$siteName}, a tourism company. "
                . "Be helpful, concise, and enthusiastic about travel. Use emojis sparingly. "
                . "Contact: {$email}, {$phone}. "
                . "Available tours:\n{$tourList}\n"
                . "Destinations: {$destinations}. "
                . "For booking: users click 'Book This Tour' on any tour page — no upfront payment, team confirms within 24 hours. "
                . "Keep replies under 200 words. Use simple markdown for formatting (bold with **).";

            $response = Http::timeout(10)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]]
                    ],
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $message]]]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 300,
                    ],
                ]
            );

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                if ($text) {
                    return ['text' => trim($text), 'type' => 'gemini'];
                }
            }

            Log::warning('Gemini API response issue', ['status' => $response->status(), 'body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::warning('Gemini API error: ' . $e->getMessage());
        }

        return null;
    }

    private function buildReply(string $message): array
    {
        $siteName = $this->generalSettings->siteName;
        $email    = $this->generalSettings->contactEmail;
        $phone    = $this->generalSettings->contactPhone;
        $address  = $this->generalSettings->address;

        if ($this->matches($message, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'start', 'help'])) {
            return ['text' => "Hi there! 👋 Welcome to **{$siteName}**!\n\nI can help you with:\n• 🗺️ Tour packages & prices\n• 📍 Destinations\n• 📅 How to book a tour\n• 📞 Contact information\n\nWhat would you like to know?"];
        }

        if ($this->matches($message, ['tours', 'tour packages', 'what tours', 'available tours', 'show tours', 'packages', 'what do you offer', 'offerings'])) {
            $tours = Tour::where('is_published', true)->with('destination')->take(6)->get();
            if ($tours->isEmpty()) {
                return ['text' => "We're currently updating our tour packages. Please contact us at **{$email}** or call **{$phone}** for the latest offerings."];
            }
            $list = $tours->map(fn($t) =>
                "• **{$t->title}**" .
                ($t->duration ? " — {$t->duration}" : '') .
                ($t->price ? ' — $' . number_format($t->discount_price ?? $t->price, 0) . '/person' : '')
            )->join("\n");
            return ['text' => "Here are our available tour packages:\n\n{$list}\n\nWould you like details on any specific tour, or are you ready to book? 😊", 'type' => 'tours'];
        }

        if ($this->matches($message, ['price', 'cost', 'how much', 'pricing', 'rates', 'fees', 'cheap', 'affordable', 'expensive'])) {
            $tours = Tour::where('is_published', true)->whereNotNull('price')->take(4)->get();
            if ($tours->isEmpty()) {
                return ['text' => "Please contact us at **{$email}** or call **{$phone}** for our current pricing."];
            }
            $list = $tours->map(fn($t) =>
                "• **{$t->title}**: $" . number_format($t->discount_price ?? $t->price, 0) . '/person'
                . ($t->discount_price ? ' ~~$' . number_format($t->price, 0) . '~~' : '')
            )->join("\n");
            return ['text' => "Here's a quick look at our pricing:\n\n{$list}\n\nPrices are per person. Group discounts may be available — contact us for details! 💬"];
        }

        if ($this->matches($message, ['destination', 'destinations', 'where', 'countries', 'places', 'location', 'visit', 'go to', 'travel to'])) {
            $destinations = Destination::take(8)->get();
            if ($destinations->isEmpty()) {
                return ['text' => "We operate tours to a variety of exciting destinations! Contact us at **{$email}** for a full list."];
            }
            $list = $destinations->map(fn($d) => "• {$d->name}")->join("\n");
            return ['text' => "We offer tours to these amazing destinations:\n\n{$list}\n\nInterested in a specific place? I can help you find the perfect tour! 🌍"];
        }

        if ($this->matches($message, ['book', 'booking', 'reserve', 'reservation', 'how to book', 'make a booking', 'sign up', 'register'])) {
            return ['text' => "Booking is easy! Here's how:\n\n1. **Browse** our tours on the website\n2. **Click** the \"Book This Tour\" button\n3. **Fill in** your details (name, email, travel date, travelers)\n4. **Submit** — no payment required upfront!\n\nOur team confirms within **24 hours**. Want me to help you find a tour? 🎒", 'type' => 'booking'];
        }

        if ($this->matches($message, ['contact', 'phone', 'call', 'email', 'address', 'reach you', 'get in touch', 'whatsapp', 'message'])) {
            return ['text' => "You can reach us through:\n\n📧 **Email:** {$email}\n📞 **Phone:** {$phone}\n📍 **Address:** {$address}\n\nWe're happy to help with any questions or custom tour requests!"];
        }

        if ($this->matches($message, ['group', 'family', 'private', 'custom', 'honeymoon', 'corporate', 'team', 'wedding', 'anniversary'])) {
            return ['text' => "We love organizing **custom & group tours**! 🎉\n\nWhether it's a family vacation, honeymoon, corporate retreat, or special celebration — we tailor every detail to your needs.\n\n📧 **{$email}**\n📞 **{$phone}**"];
        }

        if ($this->matches($message, ['duration', 'how long', 'days', 'nights', 'week', 'weekend'])) {
            $tours = Tour::where('is_published', true)->whereNotNull('duration')->take(5)->get();
            if ($tours->isNotEmpty()) {
                $list = $tours->map(fn($t) => "• **{$t->title}**: {$t->duration}")->join("\n");
                return ['text' => "Here are the durations for our tours:\n\n{$list}\n\nLooking for a specific trip length? I can help narrow it down! ⏳"];
            }
            return ['text' => "Our tours range from weekend getaways to multi-week adventures. Contact us at **{$email}** for specific details!"];
        }

        if ($this->matches($message, ['safe', 'safety', 'visa', 'passport', 'insurance', 'travel insurance', 'health', 'vaccine', 'vaccination'])) {
            return ['text' => "Great questions!\n\n🛡️ **Safety:** All tours include experienced local guides and follow safety protocols.\n✈️ **Visa:** Requirements vary — we provide full guidance during booking confirmation.\n🏥 **Insurance:** We strongly recommend travel insurance.\n\nContact us at **{$email}** for specific advice!"];
        }

        if ($this->matches($message, ['included', 'include', "what's included", 'meals', 'food', 'hotel', 'accommodation', 'transport'])) {
            return ['text' => "Each tour is different, but typically includes:\n\n✅ Professional tour guide\n✅ Transportation during the tour\n✅ Accommodation (varies by package)\n✅ Selected meals\n✅ Park/entry fees\n\nExclusions are listed on each tour page. Want me to find a specific tour's details?"];
        }

        if ($this->matches($message, ['thank', 'thanks', 'thank you', 'great', 'perfect', 'awesome', 'bye', 'goodbye'])) {
            return ['text' => "You're very welcome! 😊 We're excited to help you plan your next adventure!\n\nHave more questions anytime. Have a wonderful day! 🌟"];
        }

        return ['text' => "I'm not quite sure about that, but I'd love to help! 😊\n\nAsk me about:\n• 🗺️ Tour packages\n• 💰 Prices & what's included\n• 📍 Destinations\n• 📅 How to book\n• 📞 Contact us\n\nOr reach us at **{$email}** or **{$phone}**!"];
    }

    private function matches(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }
}
