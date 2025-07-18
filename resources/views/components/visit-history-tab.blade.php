<!-- Visit History Tab -->
<div id="history-tab" class="tab-content hidden">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Visit History</h2>
        <p class="text-gray-600">Review your past guest invitations and visit records.</p>
    </div>

    <div class="space-y-4">
        @forelse($visitHistory as $visit)
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-lg font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                        @if($visit->status == 'completed' || $visit->is_checked_out)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Completed
                            </span>
                        @elseif($visit->status == 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Cancelled
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> No Show
                            </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                    <div class="space-y-1">
                        <p class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $visit->visitor->email }}
                        </p>
                        @if($visit->visitor->organization)
                            <p class="flex items-center">
                                <i class="fas fa-building mr-2"></i>
                                {{ $visit->visitor->organization }}
                            </p>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <p class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Scheduled: {{ $visit->visit_time }}
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $floorOptions[$visit->floor_of_visit] ?? $visit->floor_of_visit }}
                        </p>
                    </div>
                    @if($visit->checked_in_at)
                        <div class="space-y-1">
                            <p class="flex items-center">
                                <i class="fas fa-sign-in-alt mr-2 text-green-600"></i>
                                Arrived: {{ \Carbon\Carbon::parse($visit->checked_in_at)->format('h:i A') }}
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-sign-out-alt mr-2 text-blue-600"></i>
                                Departed: {{ $visit->checked_out_at ? \Carbon\Carbon::parse($visit->checked_out_at)->format('h:i A') : 'N/A' }}
                            </p>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Reason:</span> {{ $visit->reason }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-exclamation-circle mx-auto text-4xl text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No visit history found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Your guest visits will appear here once completed.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($visitHistory->hasPages())
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing {{ $visitHistory->firstItem() }} to {{ $visitHistory->lastItem() }} of {{ $visitHistory->total() }} results
        </div>
        <div class="flex space-x-2">
            @if($visitHistory->onFirstPage())
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    Previous
                </button>
            @else
                <a href="{{ $visitHistory->previousPageUrl() }}#history" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    Previous
                </a>
            @endif

            @foreach(range(1, $visitHistory->lastPage()) as $page)
                @if($page == $visitHistory->currentPage())
                    <button class="px-3 py-2 text-sm bg-[#07AF8B] hover:bg-[#007570] text-white rounded-md ">
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $visitHistory->url($page) }}#history" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($visitHistory->hasMorePages())
                <a href="{{ $visitHistory->nextPageUrl() }}#history" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    Next
                </a>
            @else
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    Next
                </button>
            @endif
        </div>
    </div>
    @endif
</div>
