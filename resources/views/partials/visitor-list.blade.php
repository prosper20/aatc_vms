<div class="w-full border-t md:border-t-0">
    @forelse($visitors->sortByDesc('created_at') as $visit)
        <div x-data="visitHandler({{ $visit->id }})" class="bg-white border-b overflow-hidden transition-all duration-300" id="visit-{{ $visit->id }}">
            <!-- Compact Header (Always Visible) -->
            <div @click="expanded = !expanded" class="cursor-pointer p-4 hover:bg-gray-100 grid grid-cols-3 w-full">
                <!-- Visitor -->
                <div class="flex items-center space-x-4 w-full">
                    <h3 class="font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                </div>

                <!-- Staff -->
                <div class="flex items-center justify-center space-x-4 w-full">
                    <h3 class="font-medium text-gray-900">{{ $visit->staff->name }}</h3>
                </div>

                <!-- Date + Icon -->
                <div class="flex items-center justify-end gap-4 w-full">
                    <span class="text-sm text-gray-500">
                        @if($visit->visit_date)
                            {{ \Carbon\Carbon::parse($visit->visit_date)->diffForHumans() }}
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': expanded }"></i>
                </div>
            </div>

            <!-- Expandable Content (Hidden by Default) -->
            <div x-show="expanded" x-collapse class="px-4 pb-4 border-t">
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mt-4">
                    <!-- Column 1 -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-phone text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->phone }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-envelope text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->email }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-building text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->organization ?? 'No organization' }}</span>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar-day text-emerald-600 mt-0.5"></i>
                            <span>
                                @if($visit->visit_date)
                                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('D, M j, Y \a\t g:i A') }}
                                @else
                                    No date set
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-emerald-600 mt-0.5"></i>
                            <span>Floor: {{ $visit->floor_of_visit ?? 'Not specified' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Visit Purpose -->
                <div class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">Visit Purpose</h4>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $visit->reason ?? 'No reason provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4 pt-4">
                    <button
                        @click="approveVisit()"
                        :disabled="loading"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-emerald-700'"
                        class="flex-1 sm:flex-none bg-emerald-600 text-white font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2 transition-all duration-200"
                    >
                        <template x-if="!loading">
                            <i class="fas fa-check-circle"></i>
                        </template>
                        <template x-if="loading">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </template>
                        <span x-text="loading ? 'Approving...' : 'Approve'"></span>
                    </button>

                    <button @click="denyVisit()" class="flex-1 sm:flex-none bg-white border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2 transition-all duration-200">
                        <i class="fas fa-times-circle"></i> Deny
                    </button>
                </div>
            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="text-center bg-white p-8 rounded-xl border border-gray-200 space-y-4">
            <div class="text-4xl text-emerald-500">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">{{ __('No pending approvals') }}</h3>
            <p class="text-gray-600">{{ __('All visitor requests have been processed.') }}</p>
            <button onclick="refreshAll()" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md gap-2 transition-all duration-200">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    @endforelse
</div>

<script>
// Alpine.js component for handling individual visit actions
function visitHandler(visitId) {
    return {
        expanded: false,
        loading: false,
        visitId: visitId,

        async approveVisit() {
            this.loading = true;

            try {
                const response = await fetch(`{{ url('sm/visits') }}/${this.visitId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }

                const data = await response.json();

                // Check if the operation was successful
                if (data.success) {
                    // Show success message
                    this.showToast(data.message || 'Visit approved successfully!', 'success');

                    // Get the visit element
                    const visitElement = document.getElementById(`visit-${this.visitId}`);

                    // Animate removal
                    visitElement.style.transition = 'all 0.5s ease-out';
                    visitElement.style.transform = 'translateX(100%)';
                    visitElement.style.opacity = '0';

                    // Remove element and update counters after animation
                    setTimeout(() => {
                        visitElement.remove();
                        this.updateCounters();
                    }, 500);
                } else {
                    // Handle server-side errors
                    this.showToast(data.message || 'Failed to approve visit. Please try again.', 'error');
                    this.loading = false;
                }

            } catch (error) {
                console.error('Error approving visit:', error);
                this.showToast('Failed to approve visit. Please try again.', 'error');
                this.loading = false;
            }
        },

        async denyVisit() {
            // You can implement deny logic here similar to approve
            // For now, calling the existing function if it exists
            if (typeof denyVisitor === 'function') {
                denyVisitor(this.visitId);
            } else {
                console.warn('denyVisitor function not found');
            }
        },

        showToast(message, type = 'success') {
            // Try to use existing Alpine.js toast system first
            const toastElement = document.querySelector('[x-data*="toast"]') || document.querySelector('[x-data*="open"]');

            if (toastElement && toastElement.__x) {
                const toastData = toastElement.__x.$data;
                if (toastData.message !== undefined) {
                    toastData.message = message;
                    toastData.type = type;
                    toastData.open = true;
                    return;
                }
            }

            // Fallback to creating a toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 text-white transition-all duration-300 transform translate-x-0 ${
                type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 4000);
        },

        updateCounters() {
            const pendingCountElement = document.getElementById('pending-count');
            const pendingBadgeElement = document.getElementById('pending-badge');

            if (pendingCountElement) {
                const currentCount = parseInt(pendingCountElement.textContent) || 0;
                const newCount = Math.max(0, currentCount - 1);
                pendingCountElement.textContent = newCount;

                // Update badge
                if (pendingBadgeElement) {
                    pendingBadgeElement.innerHTML = `
                        <div class="w-2 h-2 bg-[#FFCA00] rounded-full mr-2"></div>
                        ${newCount} Pending
                    `;
                }
            }
        }
    }
}

// Global refresh function (keeping it for the empty state button)
function refreshAll() {
    // You can implement global refresh logic here
    location.reload();
}

// Legacy function for deny (if you have other code depending on it)
function denyVisitor(visitId) {
    // Implement deny logic here or redirect to Alpine.js component
    const visitElement = document.getElementById(`visit-${visitId}`);
    if (visitElement && visitElement.__x) {
        visitElement.__x.$data.denyVisit();
    }
}
</script>

{{-- <div class="w-full border-t">
    @forelse($visitors->sortByDesc('created_at') as $visit)
        {{-- <div x-data="{ expanded: false }" class="bg-white border-b overflow-hidden transition-all"> -1-}}
        <div x-data="{ expanded: false, loading: false }" class="bg-white border-b overflow-hidden transition-all">


            <div @click="expanded = !expanded" class="cursor-pointer p-4 hover:bg-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        @if($visit->visit_date)
                            {{ \Carbon\Carbon::parse($visit->visit_date)->diffForHumans() }}
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': expanded }"></i>
                </div>
            </div>

            <!-- Expandable Content (Hidden by Default) -->
            <div x-show="expanded" x-collapse class="px-4 pb-4 border-t">
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mt-4">
                    <!-- Column 1 -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-phone text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->phone }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-envelope text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->email }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-building text-emerald-600 mt-0.5"></i>
                            <span>{{ $visit->visitor->organization ?? 'No organization' }}</span>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar-day text-emerald-600 mt-0.5"></i>
                            <span>
                                @if($visit->visit_date)
                                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('D, M j, Y \a\t g:i A') }}
                                @else
                                    No date set
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-emerald-600 mt-0.5"></i>
                            <span>Floor: {{ $visit->floor_of_visit ?? 'Not specified' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Visit Purpose -->
                <div class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-1">Visit Purpose</h4>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $visit->reason ?? 'No reason provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4 pt-4">
                    {{-- <button onclick="approveVisitor({{ $visit->id }})" class="flex-1 sm:flex-none bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Approve
                    </button> -1-}}
                    <button
                        @click="approveVisitor({{ $visit->id }}, $el)"
                        :disabled="loading"
                        x-bind:class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                        class="flex-1 sm:flex-none bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2"
                    >
                        <template x-if="!loading">
                            <i class="fas fa-check-circle"></i>
                        </template>
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Approving...' : 'Approve'"></span>
                    </button>

                    <button onclick="denyVisitor({{ $visit->id }})" class="flex-1 sm:flex-none bg-white border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Deny
                    </button>
                </div>
            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="text-center bg-white p-8 rounded-xl border border-border/30 space-y-4">
            <div class="text-4xl text-emerald-500">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">{{ __('All caught up!') }}</h3>
            <p class="text-gray-600">{{ __('No pending visitor approvals at this time.') }}</p>
            <button onclick="refreshAll()" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    @endforelse
</div> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function approveVisitor(id, el) {
        const wrapper = el.closest('[x-data]');
        wrapper.__x.$data.loading = true;

        fetch(`{{ url('sm/visits') }}/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                showToast('Visit approved successfully', 'success');

                // Animate removal of the approved item (or refresh list)
                wrapper.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => {
                    wrapper.remove();
                }, 500);

            } else {
                showToast('Failed to approve visit', 'error');
                wrapper.__x.$data.loading = false;
            }
        }).catch(error => {
            showToast('Network error occurred', 'error');
            wrapper.__x.$data.loading = false;
        });
    }
// function approveVisitor(id) {
//     fetch(`{{ url('sm/visits') }}/${id}/approve`, {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': '{{ csrf_token() }}',
//             'Accept': 'application/json',
//             'Content-Type': 'application/json'
//         }
//     }).then(response => {
//         if (response.ok) {
//             showToast('Visit approved successfully', 'success');
//             refreshAll();
//         } else {
//             showToast('Failed to approve visit', 'error');
//         }
//     }).catch(error => {
//         showToast('Network error occurred', 'error');
//     });
// }

function denyVisitor(id) {
    fetch('{{ route("sm.visits.deny", "") }}/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            showToast('Visit denied successfully', 'success');
            refreshAll();
        } else {
            showToast('Failed to deny visit', 'error');
        }
    }).catch(error => {
        showToast('Network error occurred', 'error');
    });
}

function showToast(message, type) {
    // Implement your toast notification system here
    // Example using a simple alert for demonstration
    alert(`${type.toUpperCase()}: ${message}`);
}
</script> --}}
