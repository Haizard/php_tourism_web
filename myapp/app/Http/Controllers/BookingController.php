<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingNotification;
use App\Models\Booking;
use App\Models\Tour;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(Request $request, $tour)
    {
        $tour = Tour::findOrFail($tour);

        $validated = $request->validate([
            'guest_name'          => 'required|string|max:255',
            'guest_email'         => 'required|email|max:255',
            'guest_phone'         => 'required|string|max:20',
            'travel_date'         => 'required|date|after:today',
            'number_of_travelers' => 'required|integer|min:1',
            'special_requests'    => 'nullable|string|max:1000',
        ]);

        $total_price = ($tour->discount_price ?? $tour->price) * $validated['number_of_travelers'];

        $booking = Booking::create([
            'tour_id' => $tour->id,
            ...$validated,
            'total_price' => $total_price,
            'status'      => 'pending',
        ]);

        // Load relationship for emails
        $booking->load('tour.destination');

        // Send emails if mail is enabled
        $mailSettings    = app(MailSettings::class);
        $generalSettings = app(GeneralSettings::class);

        if ($mailSettings->mailEnabled) {
            try {
                // Customer confirmation
                Mail::to($booking->guest_email)->send(
                    new BookingConfirmation($booking, $generalSettings, $mailSettings)
                );

                // Admin notification
                Mail::to($mailSettings->adminNotificationEmail)->send(
                    new BookingNotification($booking, $generalSettings, $mailSettings)
                );
            } catch (\Throwable $e) {
                Log::error('Booking email failed: ' . $e->getMessage(), [
                    'booking_id' => $booking->id,
                ]);
            }
        }

        // Check whether request came from modal (AJAX-like redirect) or direct tour page form
        $successMsg = 'Booking submitted! We will confirm your booking soon.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        return redirect()
            ->route('tours.show', [
                'locale' => $request->route('locale'),
                'slug'   => $tour->slug,
            ])
            ->with('success', $successMsg);
    }
}
