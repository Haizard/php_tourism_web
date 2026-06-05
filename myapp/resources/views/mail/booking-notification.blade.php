<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Booking Notification</title>
<style>
  body { margin: 0; padding: 0; background: #f8fafc; font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 32px; text-align: center; }
  .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; }
  .header p { margin: 6px 0 0; color: rgba(255,255,255,0.7); font-size: 14px; }
  .alert-banner { background: #fef9c3; border-left: 4px solid #eab308; padding: 14px 20px; font-size: 14px; font-weight: 600; color: #713f12; }
  .body { padding: 32px; }
  .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 16px 0; }
  .card-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; margin: 0 0 14px; }
  .row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
  .row:last-child { border-bottom: none; }
  .row-label { color: #64748b; }
  .row-value { font-weight: 600; }
  .price-highlight { background: #dcfce7; border-radius: 8px; padding: 14px 18px; display: flex; justify-content: space-between; margin-top: 12px; }
  .cta { display: block; text-align: center; margin: 24px 0; }
  .btn { display: inline-block; background: #7c3aed; color: #ffffff; text-decoration: none; border-radius: 8px; padding: 12px 28px; font-weight: 700; font-size: 15px; }
  .footer { background: #f1f5f9; padding: 20px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🔔 New Booking Received</h1>
    <p>{{ $generalSettings->siteName }} — Admin Notification</p>
  </div>

  <div class="alert-banner">
    A new booking has been submitted and requires your review.
  </div>

  <div class="body">
    <div class="card">
      <p class="card-title">Tour &amp; Booking Info</p>
      <div class="row">
        <span class="row-label">Tour</span>
        <span class="row-value">{{ $booking->tour->title }}</span>
      </div>
      @if($booking->tour->destination)
      <div class="row">
        <span class="row-label">Destination</span>
        <span class="row-value">{{ $booking->tour->destination->name }}</span>
      </div>
      @endif
      <div class="row">
        <span class="row-label">Travel Date</span>
        <span class="row-value">{{ $booking->travel_date->format('F d, Y') }}</span>
      </div>
      <div class="row">
        <span class="row-label">Travelers</span>
        <span class="row-value">{{ $booking->number_of_travelers }}</span>
      </div>
      <div class="row">
        <span class="row-label">Booking Ref</span>
        <span class="row-value">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
      </div>
      <div class="price-highlight">
        <span style="font-weight:600;color:#166534;">Estimated Revenue</span>
        <span style="font-weight:800;font-size:18px;color:#166534;">${{ number_format($booking->total_price, 2) }}</span>
      </div>
    </div>

    <div class="card">
      <p class="card-title">Guest Information</p>
      <div class="row">
        <span class="row-label">Name</span>
        <span class="row-value">{{ $booking->guest_name }}</span>
      </div>
      <div class="row">
        <span class="row-label">Email</span>
        <span class="row-value">{{ $booking->guest_email }}</span>
      </div>
      <div class="row">
        <span class="row-label">Phone</span>
        <span class="row-value">{{ $booking->guest_phone }}</span>
      </div>
      @if($booking->special_requests)
      <div class="row">
        <span class="row-label">Special Requests</span>
        <span class="row-value">{{ $booking->special_requests }}</span>
      </div>
      @endif
    </div>

    <div class="cta">
      <a class="btn" href="{{ url('/admin/bookings') }}">View in Admin Panel →</a>
    </div>
  </div>

  <div class="footer">
    <p>This is an automated notification from {{ $generalSettings->siteName }}.</p>
  </div>
</div>
</body>
</html>
