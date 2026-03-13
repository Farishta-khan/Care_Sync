<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->after('id');
            $table->unsignedBigInteger('availability_id')->after('doctor_id');
            $table->time('slot_time')->after('availability_id');
            $table->boolean('is_booked')->default(false)->after('slot_time');

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('availability_id')->references('id')->on('doctor_availabilities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['availability_id']);
            $table->dropColumn(['doctor_id', 'availability_id', 'slot_time', 'is_booked']);
        });
    }
};
