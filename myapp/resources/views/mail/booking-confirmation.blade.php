<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmation</title>
<style>
  body { margin: 0; padding: 0; background: #f8fafc; font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%); padding: 40px 32px; text-align: center; }
  .header h1 { margin: 0; color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
  .header p { margin: 8px 0 0; color: rgba(255,255,255,0.85); font-size: 15px; }
  .body { padding: 36px 32px; }
  .greeting { font-size: 17px; margin-bottom: 20px; }
  .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 24px 0; }
  .card-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; margin: 0 0 16px; }
  .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
  .row:last-child { border-bottom: none; }
  .row-label { color: #64748b; }
  .row-value { font-weight: 600; text-align: right; }
  .price-row { background: linear-gradient(135deg, #7c3aed15, #ec489915); border-radius: 8px; padding: 12px 16px; margin-top: 16px; display: flex; justify-content: space-between; align-items: center; }
  .price-label { font-weight: 600; color: #7c3aed; }
  .price-value { font-size: 22px; font-weight: 800; color: #7c3aed; }
  .cta { text-align: center; margin: 28px 0; }
  .badge { display: inline-block; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 999px; padding: 6px 18px; font-size: 13px; font-weight: 600; }
  .footer { background: #f1f5f9; padding: 24px 32px; text-align: center; font-size: 13px; color: #94a3b8; }
  .footer a { color: #7c3aed; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>✈️ Booking Confirmed!</h1>
    <p>Thank you for choosing {{ $generalSettings->siteName }}</p>
  </div>

  <div class="body">
    <p class="greeting">Hi <strong>{{ $booking->guest_name }}</strong>,</p>
    <p style="color:#475569;font-size:15px;line-height:1.6;">
      Great news — we've received your booking request and our team will review and confirm it shortly.
      Here's a summary of your booking:
    </p>

    <div class="card">
      <p class="card-title">Tour Details</p>
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
      @if($booking->tour->duration)
      <div class="row">
        <span class="row-label">Duration</span>
        <span class="row-value">{{ $booking->tour->duration }}</span>
      </div>
      @endif
      <div class="row">
        <span class="row-label">Travel Date</span>
        <span class="row-value">{{ $booking->travel_date->format('F d, Y') }}</span>
      </div>
      <div class="row">
        <span class="row-label">Travelers</span>
        <span class="row-value">{{ $booking->number_of_travelers }} {{ Str::plural('person', $booking->number_of_travelers) }}</span>
      </div>
      <div class="price-row">
        <span class="price-label">Estimated Total</span>
        <span class="price-value">${{ number_format($booking->total_price, 2) }}</span>
      </div>
    </div>

    <div class="card">
      <p class="card-title">Your Contact Details</p>
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
        <span class="row-value" style="max-width:300px;">{{ $booking->special_requests }}</span>
      </div>
      @endif
    </div>

    <div class="cta">
      <span class="badge">⏳ Status: Pending Confirmation</span>
    </div>

    <p style="color:#475569;font-size:14px;line-height:1.6;">
      Our team will contact you within 24 hours to confirm availability and arrange payment.
      If you have any questions, please reply to this email or contact us at
      <a href="mailto:{{ $generalSettings->contactEmail }}" style="color:#7c3aed;">{{ $generalSettings->contactEmail }}</a>.
    </p>
  </div>

  <div class="footer">
    <p>© {{ date('Y') }} {{ $generalSettings->siteName }} &nbsp;·&nbsp; {{ $generalSettings->address }}</p>
    <p style="margin-top:6px;"><a href="mailto:{{ $generalSettings->contactEmail }}">{{ $generalSettings->contactEmail }}</a></p>
  </div>
</div>
</body>
</html>
