<h1>Appointment Status Updated</h1>
<p>Dear {{ $appointment->patient->name }},</p>
<p>The status of your appointment with Dr. {{ $appointment->doctor->name }} on {{ $appointment->appointment_date }} has been updated to: <strong>{{ strtoupper($appointment->status) }}</strong>.</p>
<br>
<p>Thank you for using CareSync!</p>
