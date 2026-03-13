<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
        {{ __('Doctor Dashboard') }}
        </h2>
    </x-slot>

    <style>
        .tab-btn {
            color: #6b7280;
            background-color: #f8fafc;
            border: 1px solid #d1d5db;
        }
        .tab-btn.active {
            color: #0f766e;
            background-color: #ecfdf5;
            border-color: #2dd4bf;
        }
        .tab-btn:hover {
            color: #0f766e;
            background-color: #d9f99d;
        }
    </style>

    <div class="py-12 min-h-[80vh] relative overflow-hidden">

        <!-- Background decoration -->
    <div class="absolute top-20 left-10 w-72 h-72 rounded-full bg-indigo-300/10 blur-[100px] -z-10"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-blue-400/10 blur-[120px] -z-10"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl overflow-hidden shadow-xl shadow-indigo-500/20 mb-8 relative">
                <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-sm"></div>
                <div class="relative p-8 sm:p-10 flex flex-col sm:flex-row justify-between items-center text-white z-10">
                    <div class="mb-6 sm:mb-0 text-center sm:text-left flex items-center gap-4">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-16 w-16 rounded-full object-cover border-2 border-white" />
                        <div>
                            <h3 class="text-3xl font-extrabold mb-2">Welcome, Dr. {{ explode(' ', Auth::user()->name)[0] }} 🩺</h3>
                            <p class="text-indigo-50 dark:text-indigo-100">Here is your schedule and pending appointment requests.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 bg-white/20 px-6 py-3 rounded-2xl backdrop-blur-md">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $appointments->where('status', 'pending')->count() }}</div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-indigo-50">Pending</div>
                        </div>
                        <div class="w-px h-10 bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $appointments->where('status', 'approved')->count() }}</div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-indigo-50">Approved</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Notification Container -->
            <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden shadow-xl sm:rounded-3xl">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Appointments Overview
                        </h3>
                        
                        <!-- Toggle Tab (Optional but clean) -->
                        <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-xl w-fit">
                            <button onclick="switchTab('appointments')" id="btn-appointments" class="tab-btn px-4 py-2 rounded-lg text-sm font-bold transition-all active">Appointments</button>
                            <button onclick="switchTab('availability')" id="btn-availability" class="tab-btn px-4 py-2 rounded-lg text-sm font-bold transition-all">Manage Availability</button>
                        </div>
                    </div>
                    
                    <div id="tab-appointments">
                        @if($appointments->isEmpty())
                            <div class="text-center py-16 px-6">
                                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">You have no appointments scheduled.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Patient Details</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date & Time</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                        @foreach($appointments as $appointment)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold border border-slate-200 dark:border-slate-600">
                                                        {{ substr($appointment->patient->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $appointment->patient->name }}</div>
                                                        <div class="text-xs text-slate-500 font-medium mt-0.5">Booking #{{ $appointment->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                            @php
                                                $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

                                                if ($appointment->status === 'approved') {
                                                    $statusClass = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400';
                                                } elseif ($appointment->status === 'pending') {
                                                    $statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
                                                }
                                            @endphp

                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm {{ $statusClass }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                                @if($appointment->status === 'pending')
                                                    <div class="flex items-center justify-end space-x-3">
                                                        <button onclick="updateAppointmentStatus({{ $appointment->id }}, 'approve')" class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 transition-colors tooltip" title="Approve">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                        <button onclick="updateAppointmentStatus({{ $appointment->id }}, 'reject')" class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors tooltip" title="Reject">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-500 text-xs italic">Action Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div id="tab-availability" class="hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-1 bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                                <h4 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Add Availability</h4>
                                <form id="form-availability" onsubmit="saveAvailability(event)" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Select Date</label>
                                        <input type="date" name="available_date" required min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Start Time</label>
                                            <input type="time" name="start_time" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">End Time</label>
                                            <input type="time" name="end_time" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Slot Duration (Min)</label>
                                        <select name="slot_duration" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                            <option value="15">15 Minutes</option>
                                            <option value="30" selected>30 Minutes</option>
                                            <option value="45">45 Minutes</option>
                                            <option value="60">60 Minutes</option>
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class="w-full py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl">
                                        Add Availability
                                    </button>
                                </form>
                            </div>

                            <div class="lg:col-span-2">
                                <h4 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Existing Availabilities</h4>
                                <div id="availability-list" class="space-y-3">
                                    <!-- Dynamic content -->
                                    <div class="flex items-center justify-center py-12 text-slate-400">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token for Ajax requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
   /* -----------------------------
   TAB SWITCHING
--------------------------------*/
function switchTab(tab) {

    const appointments = document.getElementById('tab-appointments');
    const availability = document.getElementById('tab-availability');
    const btnAppointments = document.getElementById('btn-appointments');
    const btnAvailability = document.getElementById('btn-availability');

    appointments.classList.toggle('hidden', tab !== 'appointments');
    availability.classList.toggle('hidden', tab !== 'availability');

    btnAppointments.classList.toggle('active', tab === 'appointments');
    btnAvailability.classList.toggle('active', tab === 'availability');

    if(tab === 'availability'){
        fetchAvailabilities();
    }
}

/* -----------------------------
   FETCH DOCTOR AVAILABILITIES
--------------------------------*/
async function fetchAvailabilities(){

    const list = document.getElementById('availability-list');

    try{

        const response = await fetch('/doctor/availabilities');
        const data = await response.json();

        if(!data.length){
            list.innerHTML = `<div class="text-center py-6 text-gray-500">
                No availabilities defined yet
            </div>`;
            return;
        }

        list.innerHTML = data.map(a => `
            <div class="p-4 border rounded-xl flex justify-between">
                <div>
                    <div class="font-bold">
                        ${new Date(a.available_date).toDateString()}
                    </div>
                    <div class="text-sm text-gray-500">
                        ${a.start_time.substring(0,5)} - ${a.end_time.substring(0,5)}
                        (${a.slot_duration} min slots)
                    </div>
                </div>

                <button onclick="deleteAvailability(${a.id})"
                    class="text-red-500">Delete</button>
            </div>
        `).join('');

    }catch(err){
        showToast('Error fetching availability','error');
    }
}

/* -----------------------------
   SAVE AVAILABILITY
--------------------------------*/
async function saveAvailability(e){

    e.preventDefault();

    const payload = Object.fromEntries(
        new FormData(e.target).entries()
    );

    try{

        const res = await fetch('/doctor/availabilities',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },
            body:JSON.stringify(payload)
        });

        const data = await res.json();

        if(res.ok){
            showToast(data.message);
            e.target.reset();
            fetchAvailabilities();
        }else{
            showToast(data.message,'error');
        }

    }catch{
        showToast('Network error','error');
    }
}

/* -----------------------------
   DELETE AVAILABILITY
--------------------------------*/
async function deleteAvailability(id){

    if(!confirm('Delete this availability?')) return;

    try{

        const res = await fetch(`/doctor/availabilities/${id}`,{
            method:'DELETE',
            headers:{
                'X-CSRF-TOKEN':document
                    .querySelector('meta[name="csrf-token"]').content
            }
        });

        if(res.ok){
            showToast('Availability removed');
            fetchAvailabilities();
        }

    }catch{
        showToast('Network error','error');
    }
}

/* -----------------------------
   UPDATE APPOINTMENT STATUS
--------------------------------*/
async function updateAppointmentStatus(id,action){

    const res = await fetch(`/api/appointments/${id}/${action}`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':document
                .querySelector('meta[name="csrf-token"]').content
        }
    });

    if(res.ok){
        showToast(`Appointment ${action}d`);
        setTimeout(()=>location.reload(),1200);
    }
}

/* -----------------------------
   TOAST NOTIFICATION
--------------------------------*/
function showToast(message,type='success'){

    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');

    toast.className =
        `p-3 text-white rounded shadow ${
        type==='success' ? 'bg-green-500':'bg-red-500'
    }`;

    toast.innerText = message;

    container.appendChild(toast);

    setTimeout(()=>toast.remove(),4000);
}
    </script>
</x-app-layout>
