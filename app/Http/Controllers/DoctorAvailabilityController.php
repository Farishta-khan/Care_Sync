<?php

namespace App\Http\Controllers;

use App\Models\DoctorAvailability;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorAvailabilityController extends Controller
{
    // Fetch all availabilities with slots
        public function index()
    {
        $availabilities = DoctorAvailability::where('doctor_id', auth()->id())
            ->orderBy('available_date')
            ->get();

        return response()->json($availabilities);
    }

    // Store availability and generate slots
    public function store(Request $request)
    {
        $request->validate([
            'available_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|min:15|max:120',
        ]);

        $availability = auth()->user()->doctorAvailabilities()->create([
            'available_date' => $request->available_date,
            'start_time' => $request->start_time . ':00', // convert to H:i:s
            'end_time' => $request->end_time . ':00',
            'slot_duration' => $request->slot_duration,
        ]);

        $availability->generateSlots();

        return response()->json([
            'message' => 'Availability added and slots generated successfully.',
            'availability' => $availability->load('timeSlots')
        ]);
    }

    // Delete availability and its slots
    public function destroy($id)
    {
        $availability = auth()->user()->doctorAvailabilities()->findOrFail($id);
        $availability->timeSlots()->delete(); // remove slots
        $availability->delete();

        return response()->json(['message' => 'Availability removed successfully.']);
    }

    // Get dates with available slots for a doctor
    public function getAvailableDates($doctor_id)
    {
        $dates = DoctorAvailability::where('doctor_id', $doctor_id)
            ->whereDate('available_date', '>=', now()->toDateString())
            ->pluck('available_date')
            ->unique()
            ->values();

        return response()->json($dates);
    }

    // Fetch available slots for a doctor on a specific date
    public function getAvailableSlots(Request $request, $doctor_id)
    {
        $request->validate(['date' => 'required|date']);

        $availability = DoctorAvailability::where('doctor_id', $doctor_id)
            ->whereDate('available_date', $request->date)
            ->first();

        if (!$availability) {
            return response()->json([]);
        }

        $slots = TimeSlot::where('doctor_id', $doctor_id)
            ->where('availability_id', $availability->id)
            ->where('is_booked', false)
            ->orderBy('slot_time')
            ->get(['id', 'slot_time']);

        return response()->json($slots);
    }
}