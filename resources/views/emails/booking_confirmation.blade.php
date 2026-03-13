<h1>Booking Confirmation</h1>
<p>Dear {{ $appointment->patient->name }},</p>
<p>Your appointment with Dr. {{ $appointment->doctor->name }} has been received and is currently <strong>{{ $appointment->status }}</strong>.</p>
<p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>
<p><strong>Time:</strong> {{ $appointment->start_time }} to {{ $appointment->end_time }}</p>
<br>
<p>Thank you for using CareSync!</p>
