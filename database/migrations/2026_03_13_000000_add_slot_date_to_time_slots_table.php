<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('time_slots', function (Blueprint $table) {
            if (!Schema::hasColumn('time_slots', 'slot_date')) {
                $table->date('slot_date')->nullable()->after('availability_id');
            }
        });

        // Backfill existing time slots from doctor availability
        DB::table('time_slots')
            ->join('doctor_availabilities', 'time_slots.availability_id', '=', 'doctor_availabilities.id')
            ->update(['time_slots.slot_date' => DB::raw('doctor_availabilities.available_date')]);
    }

    public function down()
    {
        Schema::table('time_slots', function (Blueprint $table) {
            if (Schema::hasColumn('time_slots', 'slot_date')) {
                $table->dropColumn('slot_date');
            }
        });
    }
};
