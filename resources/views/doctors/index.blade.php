<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight tracking-tight">
            {{ __('Find Your Perfect Doctor') }}
        </h2>
    </x-slot>

    <div class="py-12 relative overflow-hidden">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-[400px] h-[400px] rounded-full bg-emerald-400/10 blur-[100px] pointer-events-none -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] rounded-full bg-emerald-500/10 blur-[120px] pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($doctors as $doctor)
                <div class="group bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 overflow-hidden shadow-lg shadow-slate-200/50 dark:shadow-none sm:rounded-3xl hover:-translate-y-2 hover:shadow-xl transition-all duration-300 relative">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                    <div class="p-8 flex flex-col h-full relative z-10">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $doctor->avatar_url }}" alt="{{ $doctor->name }}" class="h-16 w-16 object-cover rounded-full shadow-md border-2 border-emerald-100" />
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 transition-colors">{{ $doctor->name }}</h3>
                                    <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">{{ $doctor->doctorProfile->specialty ?? 'General Practitioner' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-8 flex-grow leading-relaxed">
                            {{ $doctor->doctorProfile->bio ?? 'Dedicated to providing exceptional, personalized healthcare for all patients.' }}
                        </p>
                        
                        <div class="flex justify-between items-center px-4 py-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl mb-6">
                            <div class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                <span class="text-yellow-400 mr-1">⭐</span> 4.9 <span class="text-slate-400 font-normal ml-1 hidden sm:inline">(120)</span>
                            </div>
                            <div class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $doctor->doctorProfile->experience ?? 0 }} Yrs
                            </div>
                        </div>

                        <a href="{{ route('doctors.book', $doctor->id) }}" class="block w-full text-center bg-emerald-600 text-white hover:bg-emerald-700 font-bold py-3.5 px-4 rounded-xl transition-all duration-300 mt-auto shadow-sm hover:-translate-y-0.5 hover:shadow-md">
                            Book Appointment
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($doctors->isEmpty())
                <div class="text-center py-20 px-6 backdrop-blur-sm bg-white/50 dark:bg-slate-800/50 rounded-3xl border border-slate-200 dark:border-slate-700 max-w-2xl mx-auto shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No doctors available</h3>
                    <p class="text-slate-500 dark:text-slate-400">We're currently expanding our network. Please check back soon for world-class medical professionals.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
