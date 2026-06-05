<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Destination;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $message = strtolower(trim($request->input('message')));

        $reply = $this->buildReply($message);

        return response()->json([
            'reply' => $reply['text'],
            'type'  => $reply['type'] ?? 'text',
            'data'  => $reply['data'] ?? null,
        ]);
    }

    private function buildReply(string $message): array
    {
        $siteName = $this->generalSettings->siteName;
        $email    = $this->generalSettings->contactEmail;
        $phone    = $this->generalSettings->contactPhone;
        $address  = $this->generalSettings->address;

        // ── Greetings ──────────────────────────────────────────────────
        if ($this->matches($message, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'start', 'help'])) {
            return ['text' => "Hi there! 👋 Welcome to **{$siteName}**!\n\nI can help you with:\n• 🗺️ Tour packages & prices\n• 📍 Destinations\n• 📅 How to book a tour\n• 📞 Contact information\n\nWhat would you like to know?"];
        }

        // ── Tours list ──────────────────────────────────────────────────
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

        // ── Pricing ─────────────────────────────────────────────────────
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

        // ── Destinations ────────────────────────────────────────────────
        if ($this->matches($message, ['destination', 'destinations', 'where', 'countries', 'places', 'location', 'visit', 'go to', 'travel to'])) {
            $destinations = Destination::take(8)->get();
            if ($destinations->isEmpty()) {
                return ['text' => "We operate tours to a variety of exciting destinations! Contact us at **{$email}** for a full list."];
            }
            $list = $destinations->map(fn($d) => "• {$d->name}")->join("\n");
            return ['text' => "We offer tours to these amazing destinations:\n\n{$list}\n\nInterested in a specific place? I can help you find the perfect tour! 🌍"];
        }

        // ── Booking ─────────────────────────────────────────────────────
        if ($this->matches($message, ['book', 'booking', 'reserve', 'reservation', 'how to book', 'make a booking', 'sign up', 'register'])) {
            return ['text' => "Booking is easy! Here's how:\n\n1. **Browse** our tours on the website\n2. **Click** the \"Book This Tour\" button\n3. **Fill in** your details (name, email, travel date, number of travelers)\n4. **Submit** — no payment required upfront!\n\nOur team will confirm availability within **24 hours** and get in touch to arrange everything.\n\nWant me to help you find a tour to book? 🎒", 'type' => 'booking'];
        }

        // ── Contact ─────────────────────────────────────────────────────
        if ($this->matches($message, ['contact', 'phone', 'call', 'email', 'address', 'reach you', 'get in touch', 'whatsapp', 'message'])) {
            return ['text' => "You can reach us through any of these channels:\n\n📧 **Email:** {$email}\n📞 **Phone:** {$phone}\n📍 **Address:** {$address}\n\nWe're happy to help with any questions or custom tour requests!"];
        }

        // ── Group / custom tours ────────────────────────────────────────
        if ($this->matches($message, ['group', 'family', 'private', 'custom', 'honeymoon', 'corporate', 'team', 'wedding', 'anniversary'])) {
            return ['text' => "We absolutely love organizing **custom & group tours**! 🎉\n\nWhether it's a family vacation, honeymoon, corporate retreat, or special celebration — we tailor every detail to your needs.\n\nContact us to start planning:\n📧 **{$email}**\n📞 **{$phone}**"];
        }

        // ── Duration ─────────────────────────────────────────────────────
        if ($this->matches($message, ['duration', 'how long', 'days', 'nights', 'week', 'weekend'])) {
            $tours = Tour::where('is_published', true)->whereNotNull('duration')->take(5)->get();
            if ($tours->isNotEmpty()) {
                $list = $tours->map(fn($t) => "• **{$t->title}**: {$t->duration}")->join("\n");
                return ['text' => "Here are the durations for our tours:\n\n{$list}\n\nLooking for a specific length of trip? I can help narrow it down! ⏳"];
            }
            return ['text' => "Our tours range from weekend getaways to multi-week adventures. Contact us at **{$email}** for specific details!"];
        }

        // ── Safety / visa ─────────────────────────────────────────────────
        if ($this->matches($message, ['safe', 'safety', 'visa', 'passport', 'insurance', 'travel insurance', 'health', 'vaccine', 'vaccination'])) {
            return ['text' => "Great questions! Here's some guidance:\n\n🛡️ **Safety:** All our tours include experienced local guides and follow safety protocols.\n\n✈️ **Visa:** Requirements vary by destination and nationality. We'll provide full guidance during booking confirmation.\n\n🏥 **Insurance:** We strongly recommend travel insurance. We can point you to trusted providers.\n\nFor specific advice, contact us at **{$email}**!"];
        }

        // ── What's included ─────────────────────────────────────────────
        if ($this->matches($message, ['included', 'include', 'what\'s included', 'meals', 'food', 'hotel', 'accommodation', 'transport'])) {
            return ['text' => "Each tour package is different, but typically includes:\n\n✅ Professional tour guide\n✅ Transportation during the tour\n✅ Accommodation (varies by package)\n✅ Selected meals\n✅ Park/entry fees\n\nExclusions are clearly listed on each tour page. Want me to help you find a specific tour's details?"];
        }

        // ── Thank you ────────────────────────────────────────────────────
        if ($this->matches($message, ['thank', 'thanks', 'thank you', 'great', 'perfect', 'awesome', 'bye', 'goodbye'])) {
            return ['text' => "You're very welcome! 😊 We're excited to help you plan your next adventure!\n\nDon't hesitate to ask if you have more questions. Have a wonderful day! 🌟"];
        }

        // ── Fallback ─────────────────────────────────────────────────────
        return ['text' => "I'm not quite sure how to answer that, but I'd love to help! 😊\n\nYou can ask me about:\n• 🗺️ Our tour packages\n• 💰 Prices & what's included\n• 📍 Destinations we cover\n• 📅 How to make a booking\n• 📞 How to contact us\n\nOr reach us directly at **{$email}** or **{$phone}** — our team is happy to chat!"];
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
