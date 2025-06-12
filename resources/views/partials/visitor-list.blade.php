<div class="w-full border-t border-gray-400">
    @forelse($visitors as $visit)
        <div x-data="{ expanded: false }" class="bg-white border-b border-gray-400 overflow-hidden transition-all">
            <!-- Compact Header (Always Visible) -->
            <div @click="expanded = !expanded" class="cursor-pointer p-4 hover:bg-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    {{-- <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                        Pending
                    </span> --}}
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
            <div x-show="expanded" x-collapse class="px-4 pb-4 border-t border-gray-400">
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
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4 pt-4 ">
                    <button onclick="approveVisitor({{ $visit->id }})" class="flex-1 sm:flex-none bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Approve
                    </button>
                    <button onclick="denyVisitor({{ $visit->id }})" class="flex-1 sm:flex-none bg-white border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-md flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Deny
                    </button>
                </div>
            </div>
        </div>

        {{-- <div x-data="{ expanded: false }" class="bg-white border-b border-gray-400 overflow-hidden transition-all">
            <!-- Compact Header (Always Visible) -->
            <div @click="expanded = !expanded" class="cursor-pointer p-4 hover:bg-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                        Pending
                    </span>
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        @if($visit->visit_date)
                            {{ \Carbon\Carbon::parse($visit->visit_date)->diffForHumans() }}
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': expanded }"></i>
                </div>
            </div> --}}
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
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function approveVisitor(id) {
    if (confirm('Are you sure you want to approve this visit?')) {
        fetch('{{ route("sm.visits.approve", "") }}/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                showToast('Visit approved successfully', 'success');
                refreshAll();
            } else {
                showToast('Failed to approve visit', 'error');
            }
        }).catch(error => {
            showToast('Network error occurred', 'error');
        });
    }
}

function denyVisitor(id) {
    if (confirm('Are you sure you want to deny this visit?')) {
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
}

function showToast(message, type) {
    // Implement your toast notification system here
    // Example using a simple alert for demonstration
    alert(`${type.toUpperCase()}: ${message}`);
}
</script>

{{-- <div class="w-full divide-y divide-gray-200">
    @forelse($visitors as $visit)
        <div x-data="{ open: false }" class="border border-gray-200 rounded-md overflow-hidden transition-all mb-4">
            <!-- Compact Header Row -->
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition text-left">
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 text-sm text-gray-800">
                    <span class="font-semibold">{{ $visit->visitor->name }}</span>
                    <span class="text-gray-500">{{ $visit->visitor->phone }}</span>
                </div>
                <div class="text-sm text-gray-500 flex items-center gap-1">
                    <i class="fas fa-clock"></i>
                    @if($visit->visit_date)
                        {{ \Carbon\Carbon::parse($visit->visit_date)->diffForHumans() }}
                    @endif
                    <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-2 transition"></i>
                </div>
            </button>

            <!-- Expandable Content -->
            <div x-show="open" x-collapse class="bg-gray-50 p-4 text-sm text-gray-700 space-y-4">
                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="flex items-start gap-2"><i class="fas fa-envelope text-emerald-600 mt-0.5"></i> <span>{{ $visit->visitor->email }}</span></div>
                        <div class="flex items-start gap-2"><i class="fas fa-building text-emerald-600 mt-0.5"></i> <span>{{ $visit->visitor->organization ?? 'No organization' }}</span></div>
                    </div>
                    <div>
                        <div class="flex items-start gap-2"><i class="fas fa-user-tie text-emerald-600 mt-0.5"></i> <span>Host: {{ $visit->staff->name }}</span></div>
                        <div class="flex items-start gap-2"><i class="fas fa-calendar-day text-emerald-600 mt-0.5"></i>
                            <span>
                                @if($visit->visit_date)
                                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('D, M j, Y \a\t g:i A') }}
                                @else
                                    No date set
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start gap-2"><i class="fas fa-map-marker-alt text-emerald-600 mt-0.5"></i> <span>Floor: {{ $visit->floor_of_visit ?? 'Not specified' }}</span></div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
                            <div>
                                <strong class="block text-gray-800 mb-1">Visit Purpose:</strong>
                                <p class="text-gray-700 whitespace-pre-wrap min-h-[3rem]">
                                    {{ $visit->reason ?? 'No reason provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-4">
                    <button onclick="approveVisitor({{ $visit->id }})"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Approve Visit
                    </button>
                    <button onclick="denyVisitor({{ $visit->id }})"
                            class="bg-white border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-md flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> Deny Visit
                    </button>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-md flex items-center gap-2">
                        <i class="fas fa-ellipsis-h"></i> More Options
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center bg-white p-10 rounded-xl shadow space-y-4">
            <div class="text-5xl text-emerald-500">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">{{ __('All Clear!') }}</h3>
            <p class="text-gray-600">{{ __('No pending visitor approvals at this time.') }}</p>
            <button onclick="refreshAll()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-6 py-2 rounded-md flex items-center justify-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    @endforelse
</div> --}}

{{-- old code start  --}}
{{-- <div class="w-full space-y-6">
    @forelse($visitors as $visit)
        <div class="bg-white border-b border-t border-border/30 hover:shadow-lg transition p-6">

            <div class="flex items-center pb-4 mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $visit->visitor->name }}</h3>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    @if($visit->visit_date)
                        {{ \Carbon\Carbon::parse($visit->visit_date)->diffForHumans() }}
                    @endif
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-700">

                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-phone text-emerald-600 mt-0.5"></i>
                        <span>{{ $visit->visitor->phone }}</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-envelope text-emerald-600 mt-0.5"></i>
                        <span>{{ $visit->visitor->email }}</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-building text-emerald-600 mt-0.5"></i>
                        <span>{{ $visit->visitor->organization ?? 'No organization' }}</span>
                    </div>
                </div>


                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-user-tie text-emerald-600 mt-0.5"></i>
                        <span>Host: {{ $visit->staff->name }}</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-calendar-day text-emerald-600 mt-0.5"></i>
                        <span>
                            @if($visit->visit_date)
                                {{ \Carbon\Carbon::parse($visit->visit_date)->format('D, M j, Y \a\t g:i A') }}
                            @else
                                No date set
                            @endif
                        </span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-emerald-600 mt-0.5"></i>
                        <span>Floor: {{ $visit->floor_of_visit ?? 'Not specified' }}</span>
                    </div>
                </div>


                {{-- <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
                        <div>
                            <strong class="block text-gray-800">Visit Purpose:</strong>
                            <p class="text-gray-600">{{ $visit->reason ?? 'No reason provided' }}</p>
                        </div>
                    </div>
                </div> --
                <div class="md:col-span-3">
                    <div class="flex items-start gap-3 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <i class="fas fa-info-circle text-emerald-600 mt-1 text-lg"></i>
                        <div class="flex-1">
                            <strong class="block text-gray-800 mb-1">Visit Purpose:</strong>
                            <p class="text-gray-700 whitespace-pre-wrap min-h-[3rem]">
                                {{ $visit->reason ?? 'No reason provided' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex flex-col md:flex-row justify-end items-stretch md:items-center gap-3 mt-6 pt-4">
                <button onclick="approveVisitor({{ $visit->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded-md flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Approve Visit
                </button>
                <button onclick="denyVisitor({{ $visit->id }})" class="bg-white border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-md flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> Deny Visit
                </button>
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-md flex items-center gap-2">
                    <i class="fas fa-ellipsis-h"></i> More Options
                </button>
            </div>
        </div>
    @empty

        <div class="text-center bg-white p-10 rounded-xl shadow space-y-4">
            <div class="text-5xl text-emerald-500">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">{{ __('All Clear!') }}</h3>
            <p class="text-gray-600">{{ __('No pending visitor approvals at this time.') }}</p>
            <button onclick="refreshAll()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-6 py-2 rounded-md flex items-center justify-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    @endforelse
</div> --}}


{{-- <script>
    function approveVisitor(id) {
        if (confirm('Are you sure you want to approve this visit?')) {
            fetch('{{ route("sm.visits.approve", "") }}/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    showToast('Visit approved successfully', 'success');
                    refreshAll();
                } else {
                    showToast('Failed to approve visit', 'error');
                }
            }).catch(error => {
                showToast('Network error occurred', 'error');
            });
        }
    }

    function denyVisitor(id) {
        if (confirm('Are you sure you want to deny this visit?')) {
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
    }

    function showToast(message, type) {
        // Implement your toast notification system here
        // Example using a simple alert for demonstration
        alert(`${type.toUpperCase()}: ${message}`);
    }
    </script> --}}
