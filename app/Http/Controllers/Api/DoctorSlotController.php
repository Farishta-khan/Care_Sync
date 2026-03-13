<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class DoctorSlotController extends Controller
{
    public function index(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = User::where('role', 'doctor')->findOrFail($id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->englishDayOfWeek;

        // Customer side uses date-specific availabilities (DoctorAvailability)
        $availabilities = $doctor->doctorAvailabilities()->whereDate('available_date', $date->toDateString())->get();
        if ($availabilities->isEmpty()) {
            return response()->json(['slots' => []]);
        }

        $appointments = $doctor->doctorAppointments()
            ->whereDate('appointment_date', $date->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $slots = [];
        $slotDuration = 30;

        foreach ($availabilities as $availability) {
            $start = Carbon::parse($availability->start_time);
            $end = Carbon::parse($availability->end_time);

            while ($start->copy()->addMinutes($slotDuration)->lte($end)) {
                $slotStart = $start->format('H:i:s');
                $slotEnd = $start->copy()->addMinutes($slotDuration)->format('H:i:s');
                
                $isBooked = $appointments->contains(function ($appointment) use ($slotStart, $slotEnd) {
                    return ($slotStart >= $appointment->start_time && $slotStart < $appointment->end_time) ||
                           ($slotEnd > $appointment->start_time && $slotEnd <= $appointment->end_time) ||
                           ($slotStart <= $appointment->start_time && $slotEnd >= $appointment->end_time);
                });

                if (!$isBooked) {
                    $slots[] = [
                        'start' => $slotStart,
                        'end'   => $slotEnd,
                    ];
                }

                $start->addMinutes($slotDuration);
            }
        }

        return response()->json(['slots' => $slots]);
    }
}
