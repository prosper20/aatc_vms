<div>
    <div class="max-w-7xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Register Walk-in Guests</h2>
            <p class="text-gray-600">Fill out the form below to register a walk-in guest.</p>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div id="success-alert" class="w-full flex justify-between relative mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <!-- Close Button -->
                <button type="button"
                        class="absolute top-2 right-2 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-green-700 hover:text-green-900 focus:outline-none transition-colors duration-200"
                        onclick="dismissAlert('success-alert')"
                        aria-label="Close notification">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>

                <span class="w-[90%]">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div id="error-alert" class="w-full flex justify-between relative mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <!-- Close Button -->
                <button type="button"
                        class="absolute top-2 right-2 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-red-700 hover:text-red-900 focus:outline-none transition-colors duration-200"
                        onclick="dismissAlert('error-alert')"
                        aria-label="Close notification">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>

                <span class="w-[90%]">{{ session('error') }}</span>
            </div>
        @endif


        <form wire:submit.prevent="submit" class="space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side - Guest Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Guest Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user text-[#07AF8B] mr-2"></i>
                            Guest Information
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="mb-6">
                                <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" wire:model="email" id="guest_email" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="guest@example.com">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6">
                                <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" wire:model="name" id="guest_name" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="Enter guest's full name">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6">
                                <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" wire:model="phone" id="guest_phone" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="+1 (555) 123-4567">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6">
                                <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organization (Optional)</label>
                                <input type="text" wire:model="organization" id="organization"
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="Company name">
                                @error('organization') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Visit Details -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-building text-[#07AF8B] mr-2"></i>
                            Visit Details
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div class="mb-6">
                                <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">Visit Date *</label>
                                <input type="date" wire:model="visit_date" id="visit_date" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       min="{{ date('Y-m-d') }}">
                                @error('visit_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6">
                                <label for="visit_time" class="block text-sm font-medium text-gray-700 mb-2">Visit Time *</label>
                                <input type="time" wire:model="visit_time" id="visit_time" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                @error('visit_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6 sm:col-span-2">
                                <label for="floor" class="block text-sm font-medium text-gray-700 mb-2">Floor/Department *</label>
                                <select wire:model="floor_of_visit" id="floor" required
                                        class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                    <option value="">Select floor</option>
                                    @foreach($floorOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('floor_of_visit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-6 sm:col-span-2" wire:key="guest-{{ $currentGuestIndex }}">
                                <label for="reason_type" class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>

                                <!-- Reason Type Dropdown -->
                                <select wire:model.live="reasonType" id="reason_type" required
                                        class="w-full mb-3 py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                    <option value="">Select reason</option>
                                    <option value="Official">Official</option>
                                    <option value="Personal">Personal</option>
                                    <option value="Other">Other</option>
                                </select>

                                <!-- Custom Reason Textarea (shown only when 'Other' is selected) -->
                                @if($reasonType === 'Other')
                                    <div class="mt-2" wire:transition>
                                        <label for="custom_reason" class="block text-sm font-medium text-gray-700 mb-2">Please specify reason *</label>
                                        <textarea wire:model.live="customReason" id="custom_reason" rows="3" required
                                                  class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                                  placeholder="Enter your specific reason for visit"></textarea>
                                        @error('customReason')
                                            <span class="text-red-500 text-sm">error</span>
                                        @enderror
                                    </div>
                                @endif

                                @error('reasonType')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Guest Management -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Guest Navigation -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Guests</h3>
                            <span class="text-sm text-gray-500">{{ count($guests) }} Guest{{ count($guests) !== 1 ? 's' : '' }}</span>
                        </div>

                        @if(count($guests) > 1)
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" wire:click="previousGuest"
                                        {{ $currentGuestIndex === 0 ? 'disabled' : '' }}
                                        class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <span class="text-sm font-medium">Guest {{ $currentGuestIndex + 1 }} of {{ count($guests) }}</span>

                                <button type="button" wire:click="nextGuest"
                                        {{ $currentGuestIndex === count($guests) - 1 ? 'disabled' : '' }}
                                        class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <button type="button" wire:click="addGuest"
                                    {{ !$this->isCurrentGuestComplete() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-plus mr-2"></i>
                                Add Another Guest
                            </button>

                            @if(count($guests) > 1)
                                <button type="button" wire:click="removeGuest"
                                        class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 text-red-600 hover:text-red-700 hover:bg-red-50">
                                    Remove Current Guest
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- CSV Import/Export -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">CSV Import/Export</h3>
                        <div class="space-y-2">
                            <div class="relative">
                                <input type="file" wire:model="csvFile" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="w-full px-4 py-3 rounded-xl text-white text-base font-medium transition-all duration-200 bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer text-center">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import Guests from CSV
                                </div>
                            </div>

                            <button type="button" wire:click="downloadTemplate"
                                    class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 hover:bg-gray-50 transition-all duration-200 active:translate-y-0 hover:-translate-y-px cursor-pointer">
                                <i class="fas fa-download mr-2"></i>
                                Download CSV Template
                            </button>
                        </div>
                    </div>

                    <!-- Current Guest Summary -->
                    <div class="hidden bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Current Guest Summary</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p><strong>Name:</strong> {{ $name ?: 'Not specified' }}</p>
                            <p><strong>Email:</strong> {{ $email ?: 'Not specified' }}</p>
                            <p><strong>Date:</strong> {{ $visit_date ?: 'Not specified' }}</p>
                            <p><strong>Floor:</strong> {{ $floor_of_visit ? ($floorOptions[$floor_of_visit] ?? $floor_of_visit) : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <button type="submit" wire:loading.attr="disabled"
                        {{ !$this->isCurrentGuestComplete() ? 'disabled' : '' }}
                        class="px-4 py-3 rounded-xl text-white text-base font-medium flex items-center justify-center transition-all duration-200 bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed
                        disabled:hover:bg-[#07AF8B]
                        disabled:hover:translate-y-0
                        disabled:cursor-not-allowed">
                    <span wire:loading.remove>
                        @if(count($guests) === 1 )
                            Send Invitation
                        @else
                            Send All Invitations ({{ count($guests) }})
                        @endif
                    </span>
                    <span wire:loading>
                        Sending...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function dismissAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }
    </script>
