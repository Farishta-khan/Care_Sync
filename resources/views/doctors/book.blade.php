        <x-app-layout>
        <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
        {{ __('Book Appointment with ') . $doctor->name }}
        </h2>
        </x-slot>

        <div class="py-12 relative overflow-hidden">

        <!-- Background Blur -->
        <div class="absolute -top-20 -right-20 w-[500px] h-[500px] rounded-full bg-emerald-400/10 blur-[100px] -z-10"></div>
        <div class="absolute -bottom-20 -left-20 w-[600px] h-[600px] rounded-full bg-emerald-400/10 blur-[120px] -z-10"></div>

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

        <style>
        /* Highlight only available dates */
        .flatpickr-day.available {
            background:#22C55E !important;  /* emerald green */
            color:white !important;
            border-radius:8px !important;
            font-weight:600;
            transition: all 0.2s ease-in-out;
        }
        .flatpickr-day.available:hover {
            background:#16A34A !important;
            transform: scale(1.1);
        }
        .slot-btn {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #22C55E;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
            background-color: #ECFDF5; /* light green background */
        }
        .slot-btn:hover {
            background-color: #22C55E;
            color: white;
            transform: scale(1.05);
        }
        .slot-btn.active {
            background-color: #16A34A;
            color: white;
        }
        #slots-container {
            min-height: 150px;
        }
        #selected-slot-display {
            transition: all 0.3s ease-in-out;
        }
        </style>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">

        <!-- Doctor Card -->
        <div class="w-full lg:w-1/3">
        <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 shadow-xl rounded-3xl overflow-hidden sticky top-8">

        <div class="p-8 text-center">

        <img src="{{ $doctor->avatar_url }}" alt="{{ $doctor->name }}" class="h-28 w-28 mx-auto rounded-full object-cover shadow-lg border-4 border-emerald-200 mb-6" />

        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">
        {{ $doctor->name }}
        </h3>

        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mt-1 mb-6">
        {{ $doctor->doctorProfile->specialty ?? 'General' }}
        </p>

        <p class="text-slate-600 dark:text-slate-400 text-sm mb-8">
        {{ $doctor->doctorProfile->bio ?? 'Ready to provide the best healthcare service for you.' }}
        </p>

        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 space-y-4">

        <div class="flex justify-between">
        <span class="text-slate-500 text-sm">Experience</span>
        <span class="font-bold text-slate-800 dark:text-slate-200">
        {{ $doctor->doctorProfile->experience ?? 0 }} Years
        </span>
        </div>

        <div class="flex justify-between">
        <span class="text-slate-500 text-sm">Hourly Rate</span>
        <span class="font-bold text-slate-800 dark:text-slate-200">
        ${{ $doctor->doctorProfile->hourly_rate ?? '0.00' }}
        </span>
        </div>

        <div class="flex justify-between">
        <span class="text-slate-500 text-sm">Rating</span>
        <span class="font-bold text-slate-800 dark:text-slate-200">
        ⭐ 4.9
        </span>
        </div>

        </div>
        </div>
        </div>
        </div>

        <!-- Booking Section -->
        <div class="w-full lg:w-2/3">

        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 shadow-xl rounded-3xl p-10">

        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
        Schedule Your Visit
        </h3>

        <p class="text-slate-500 mb-8">
        Select a preferred date and time for your consultation.
        </p>

        <form id="booking-form" method="POST" action="{{ route('appointments.book') }}">
        @csrf

        <input type="hidden" id="doctor_id" name="doctor_id" value="{{ $doctor->id }}">
        <input type="hidden" id="slot_id" name="slot_id">

        <!-- Date Picker -->
        <div class="mb-8">
        <label class="block text-sm font-semibold mb-3">Select Date</label>
        <input
        type="text"
        id="appointment_date"
        name="appointment_date"
        placeholder="Loading available dates..."
        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 cursor-pointer"
        readonly
        required
        >
        </div>

        <!-- Time Slots -->
        <div class="mb-10">
        <label class="block text-sm font-semibold mb-4">Available Time Slots</label>
        <div id="slots-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 bg-slate-50 p-6 rounded-2xl"></div>
        <div id="selected-slot-display" class="mt-4 p-4 bg-emerald-50 rounded-xl hidden">
        <p class="text-sm font-bold" id="slot-text"></p>
        </div>
        </div>

        <button type="submit" id="submit-btn" disabled
        class="w-full py-4 rounded-xl text-white font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 disabled:opacity-50">
        Confirm Booking
        </button>

        </form>
        </div>
        </div>
        </div>
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
        document.addEventListener("DOMContentLoaded", async function(){

        const dateInput=document.getElementById("appointment_date");
        const slotsContainer=document.getElementById("slots-container");
        const slotIdInput=document.getElementById("slot_id");
        const submitBtn=document.getElementById("submit-btn");
        const selectedDisplay=document.getElementById("selected-slot-display");
        const slotText=document.getElementById("slot-text");
        const doctorId=document.getElementById("doctor_id").value;

        let availableDates=[];

        try{
        const res=await fetch(`/api/doctor/${doctorId}/available-dates`);
        availableDates=await res.json();
        dateInput.placeholder="Choose available date";
        }catch(e){ console.log(e); }

        /* Initialize Flatpickr */
        flatpickr(dateInput,{
        minDate:"today",
        dateFormat:"Y-m-d",
        enable:availableDates,
        onDayCreate:function(dObj,dStr,fp,dayElem){
        const dateStr=dayElem.dateObj.toISOString().split('T')[0];
        if(availableDates.includes(dateStr)){
        dayElem.classList.add("available");
        }
        },
        onChange:function(selectedDates,dateStr){
        if(dateStr) fetchSlots(dateStr);
        }
        });

        /* Fetch Time Slots */
        async function fetchSlots(date){
        slotsContainer.innerHTML=`<div class="col-span-full text-center">Loading slots...</div>`;
        slotIdInput.value="";
        submitBtn.disabled=true;
        selectedDisplay.classList.add("hidden");

        try{
        const res=await fetch(`/api/doctor/${doctorId}/available-slots?date=${date}`);
        const slots=await res.json();
        slotsContainer.innerHTML="";
        if(slots.length===0){
        slotsContainer.innerHTML=`<p class="text-red-500 col-span-full text-center">No slots available</p>`;
        return;
        }
        slots.forEach(slot=>{
        const btn=document.createElement("button");
        btn.type="button";
        const [h,m]=slot.slot_time.split(":");
        const ampm=h>=12?"PM":"AM";
        const hr=h%12||12;
        const displayTime=`${hr}:${m} ${ampm}`;
        btn.className="slot-btn";
        btn.textContent=displayTime;
        btn.onclick=function(){
        document.querySelectorAll(".slot-btn").forEach(b=>{
        b.classList.remove("active");
        });
        btn.classList.add("active");
        slotIdInput.value=slot.id;
        submitBtn.disabled=false;
        selectedDisplay.classList.remove("hidden");
        slotText.textContent=`${displayTime} on ${date}`;
        };
        slotsContainer.appendChild(btn);
        });
        }catch(err){
        slotsContainer.innerHTML=`<p class="text-red-500 col-span-full text-center">Error loading slots</p>`;
        }
        }
        });
        </script>
        </x-app-layout>