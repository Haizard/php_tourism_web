<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(Request $request, string $tour)
    {
        $tour = Tour::findOrFail($tour);
        
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'travel_date' => 'required|date|after:today',
            'number_of_travelers' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $total_price = ($tour->discount_price ?? $tour->price) * $validated['number_of_travelers'];

        $booking = Booking::create([
            'tour_id' => $tour->id,
            ...$validated,
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        // Send confirmation email (implement later with proper mail configuration)
        // Mail::send(new BookingConfirmation($booking));

        return redirect()->route('tours.show', ['locale' => $request->route('locale'), 'slug' => $tour->slug])->with('success', 'Booking submitted! We will confirm your booking soon.');
    }
}
