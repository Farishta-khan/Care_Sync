<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Patient Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 relative overflow-hidden min-h-[80vh]">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-10 w-[300px] h-[300px] rounded-full bg-blue-400/10 blur-[100px] pointer-events-none -z-10"></div>
        <div class="absolute bottom-10 left-10 w-[400px] h-[400px] rounded-full bg-purple-400/10 blur-[120px] pointer-events-none -z-10"></div>
        
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
        <style>
            .flatpickr-day.available {
                background: #22C55E !important;
                color: white !important;
                border-color: #22C55E !important;
                border-radius: 8px !important;
            }
            .flatpickr-day.available:hover {
                background: #16A34A !important;
            }
        </style>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl overflow-hidden shadow-xl shadow-blue-500/20 mb-8 relative">
                <div class="absolute inset-0 bg-white/10 dark:bg-black/10 backdrop-blur-sm"></div>
                <div class="relative p-8 sm:p-10 flex flex-col sm:flex-row justify-between items-center text-white z-10">
                    <div class="mb-6 sm:mb-0 text-center sm:text-left">
                        <h3 class="text-3xl font-extrabold mb-2">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h3>
                        <p class="text-blue-100 dark:text-blue-200">Manage your upcoming health appointments.</p>
                    </div>
                    <a href="{{ route('doctors.index') }}" class="bg-white text-blue-600 hover:bg-slate-50 dark:bg-slate-900 dark:text-white dark:hover:bg-slate-800 font-bold py-3 px-6 rounded-full shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                        Find a Doctor
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center" role="alert">
                <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            @endif
            
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center" role="alert">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
            @endif

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden shadow-xl sm:rounded-3xl">
                <div class="p-8">
                    <h3 class="text-xl font-bold mb-6 text-slate-800 dark:text-white">Your Appointments</h3>
                    @if($appointments->isEmpty())
                        <div class="text-center py-16 px-6">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">You have no upcoming appointments.</p>
                            <a href="{{ route('doctors.index') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline mt-2 inline-block">Book one now</a>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-900/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Doctor</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($appointments as $appointment)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img src="{{ $appointment->doctor->avatar_url }}" alt="{{ $appointment->doctor->name }}" class="flex-shrink-0 h-10 w-10 rounded-full object-cover shadow" />
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">Dr. {{ $appointment->doctor->name }}</div>
                                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $appointment->doctor->doctorProfile->specialty ?? 'General' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm 
                                                @if($appointment->status === 'approved') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                @elseif($appointment->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                @if($appointment->status === 'approved')
                                                    @php
                                                        $start = Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)->timezone('UTC')->format('Ymd\THis\Z');
                                                        $end = Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->end_time)->timezone('UTC')->format('Ymd\THis\Z');
                                                        $googleCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode("Appointment with Dr. " . $appointment->doctor->name) . "&dates={$start}/{$end}";
                                                    @endphp
                                                    <a href="{{ $googleCalUrl }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm text-xs font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none transition-colors" title="Add to Google Calendar">
                                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Calendar
                                                    </a>
                                                    
                                                    <button onclick="openRescheduleModal({{ $appointment->id }}, {{ $appointment->doctor_id }}, 'Dr. {{ addslashes($appointment->doctor->name) }}')" class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm text-xs font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none transition-colors" title="Reschedule Appointment">
                                                        <svg class="w-4 h-4 mr-1 text-[#0EA5A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        Reschedule
                                                    </button>
                                                @else
                                                    <span class="text-slate-400 dark:text-slate-500 text-xs italic">Awaiting Approval</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>

    <!-- Reschedule Modal -->
    <div id="reschedule-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeRescheduleModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
                <form id="reschedule-form">
                    @csrf
                    <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#0EA5A4]/10 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-[#0EA5A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white" id="modal-title">Reschedule Appointment</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Select a new date and time for your appointment with <span id="modal-doctor-name" class="font-semibold text-slate-700 dark:text-slate-300"></span>.</p>
                                    
                                    <input type="hidden" id="modal_appointment_id">
                                    <input type="hidden" id="modal_doctor_id">
                                    <input type="hidden" id="modal_start_time">
                                    
                                    <div class="mb-4">
                                        <label for="modal_appointment_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">New Date</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="text" id="modal_appointment_date" placeholder="Loading dates..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-[#0EA5A4] focus:border-[#0EA5A4] block pl-10 p-3 shadow-sm transition-colors cursor-pointer" required readonly>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Available Time Slots</label>
                                        <div id="modal-slots-container" class="grid grid-cols-2 md:grid-cols-3 gap-2 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800 min-h-[100px] items-center text-center">
                                            <p class="text-xs text-slate-500 col-span-full" id="modal-no-date-msg">Select an available date first.</p>
                                        </div>
                                        <input type="hidden" id="modal_slot_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" id="modal-submit-btn" disabled class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#0EA5A4] text-base font-bold text-white hover:bg-[#0b8e8c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0EA5A4] sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            Confirm Reschedule
                        </button>
                        <button type="button" onclick="closeRescheduleModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0EA5A4] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        let fpReschedule = null;

        // Toast logic (Medical Palette: Success bg-[#22C55E], Error bg-[#EF4444])
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgClass = type === 'success' ? 'bg-[#22C55E]' : 'bg-[#EF4444]';
            const icon = type === 'success' 
                ? '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                : '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.className = `transform transition-all duration-300 translate-x-full opacity-0 flex items-center p-4 mb-4 text-white rounded-lg shadow-lg ${bgClass}`;
            toast.innerHTML = `
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg bg-white/20">${icon}</div>
                <div class="ms-3 text-sm font-semibold">${message}</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-transparent text-white hover:text-gray-200 rounded-lg focus:ring-2 focus:ring-white p-1.5 hover:bg-white/10 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-x-full', 'opacity-0'));
            setTimeout(() => {
                if(toast.parentElement) {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }

        async function openRescheduleModal(appointmentId, doctorId, doctorName) {
            document.getElementById('reschedule-modal').classList.remove('hidden');
            document.getElementById('modal_appointment_id').value = appointmentId;
            document.getElementById('modal_doctor_id').value = doctorId;
            document.getElementById('modal-doctor-name').textContent = doctorName;
            
            // Reset fields
            document.getElementById('modal_appointment_date').value = '';
            document.getElementById('modal_appointment_date').placeholder = 'Loading available dates...';
            document.getElementById('modal_slot_id').value = '';
            document.getElementById('modal-submit-btn').disabled = true;
            document.getElementById('modal-slots-container').innerHTML = '<p class="text-xs text-slate-500 col-span-full text-center">Please select an available date.</p>';

            // 1. Fetch available dates for this specific doctor
            try {
                const res = await fetch(`/api/doctor/${doctorId}/available-dates`);
                const availableDates = await res.json();
                
                if (fpReschedule) fpReschedule.destroy();
                
                fpReschedule = flatpickr("#modal_appointment_date", {
                    minDate: "today",
                    dateFormat: "Y-m-d",
                    enable: availableDates,
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                        if (availableDates.includes(dateStr)) {
                            dayElem.classList.add('available');
                        }
                    },
                    onChange: function(selectedDates, dateStr) {
                        if (dateStr) fetchModalSlots(dateStr, doctorId);
                    }
                });
                
                document.getElementById('modal_appointment_date').placeholder = availableDates.length > 0 ? "Choose a new date" : "No dates available";
            } catch (e) {
                console.error("Failed to fetch dates", e);
                document.getElementById('modal_appointment_date').placeholder = "Error loading dates";
            }
        }

        async function fetchModalSlots(date, doctorId) {
            const slotsContainer = document.getElementById('modal-slots-container');
            const slotIdInput = document.getElementById('modal_slot_id');
            const submitBtn = document.getElementById('modal-submit-btn');
            
            slotsContainer.innerHTML = '<div class="col-span-full flex flex-col items-center py-2"><div class="animate-spin rounded-full h-5 w-5 border-b-2 border-[#0EA5A4]"></div></div>';
            slotIdInput.value = '';
            submitBtn.disabled = true;

            try {
                const response = await fetch(`/api/doctor/${doctorId}/available-slots?date=${date}`);
                const slots = await response.json();
                
                slotsContainer.innerHTML = '';
                
                if (slots.length === 0) {
                    slotsContainer.innerHTML = '<p class="text-xs text-red-500 col-span-full">⚠ All slots booked.</p>';
                    return;
                }

                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    const time = slot.slot_time.substring(0, 5);
                    const formatTime = (t) => {
                        let [h, m] = t.split(':');
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        h = h % 12 || 12;
                        return `${h}:${m} ${ampm}`;
                    };
                    const displayTime = formatTime(time);
                    
                    btn.className = 'slot-btn py-2 px-3 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none transition-all shadow-sm';
                    btn.textContent = displayTime;
                    
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('#modal-slots-container .slot-btn').forEach(b => {
                            b.classList.remove('bg-[#0EA5A4]', 'text-white', 'border-transparent');
                            b.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'dark:bg-slate-800', 'dark:text-slate-300', 'dark:border-slate-700');
                        });
                        
                        btn.classList.add('bg-[#0EA5A4]', 'text-white', 'border-transparent');
                        btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'dark:bg-slate-800', 'dark:text-slate-300', 'dark:border-slate-700');
                        
                        slotIdInput.value = slot.id;
                        submitBtn.disabled = false;
                    });
                    
                    slotsContainer.appendChild(btn);
                });
            } catch (error) {
                slotsContainer.innerHTML = '<p class="text-xs text-red-500 col-span-full">Error fetching slots.</p>';
            }
        }

        function closeRescheduleModal() {
            document.getElementById('reschedule-modal').classList.add('hidden');
        }

        // Submit Reschedule
        document.getElementById('reschedule-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('modal-submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Updating...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/api/reschedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        appointment_id: document.getElementById('modal_appointment_id').value,
                        slot_id: document.getElementById('modal_slot_id').value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    showToast(data.error || 'Failed to reschedule appointment.', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                } else {
                    closeRescheduleModal();
                    showToast('✅ Appointment rescheduled successfully.', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (error) {
                showToast('Network error occurred.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    </script>
</x-app-layout>
