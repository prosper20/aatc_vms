<!-- Active Visits Tab -->
<div id="active-tab" class="tab-content hidden">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Active Invitations</h2>
        <p class="text-gray-600">Manage your current guest invitations and their approval status.</p>
    </div>

    <div class="space-y-4">
        @forelse($activeVisits as $visit)
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ $visit->visitor->name }}</h3>
                            @if($visit->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending Approval
                                </span>
                            @elseif($visit->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Denied
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
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
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }} at {{ $visit->visit_time }}
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $floorOptions[$visit->floor_of_visit] ?? $visit->floor_of_visit }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Reason:</span> {{ $visit->reason }}
                            </p>
                        </div>

                        @if($visit->status == 'approved')
                            <div class="bg-green-50 border border-green-200 rounded-xl p-3 mb-4">
                                <p class="text-sm text-green-800">
                                    <span class="font-medium">Invitation Code:</span> {{ $visit->unique_code }}
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    Share this code with your guest for entry verification.
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="ml-6 flex flex-col space-y-2">
                        @if($visit->status == 'pending')
                            <button onclick="cancelVisit({{ $visit->id }})" class="inline-flex items-center justify-center px-3 py-1 border border-red-200 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                                Cancel
                            </button>
                            <button onclick="editVisit({{ $visit->id }})" class="inline-flex items-center justify-center px-3 py-1 border border-green-200 text-sm font-medium rounded-md text-[#00aa8c] bg-yellow-50 hover:bg-yellow-100 hover:border-yellow-100">
                                Edit
                            </button>
                        @endif
                        @if($visit->status == 'approved')
                            <button onclick="resendCode({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-gray-200 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50">
                                Resend Code
                            </button>
                        @endif
                        @if($visit->status == 'rejected')
                            <button onclick="resubmitVisit({{ $visit->id }})" class="inline-flex items-center px-3 py-1 border border-blue-200 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                Resubmit
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-exclamation-circle mx-auto text-4xl text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No active invitations</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by creating a new guest invitation.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($activeVisits->hasPages())
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing {{ $activeVisits->firstItem() }} to {{ $activeVisits->lastItem() }} of {{ $activeVisits->total() }} results
        </div>
        <div class="flex space-x-2">
            @if($activeVisits->onFirstPage())
                <button class="px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    Previous
                </button>
            @else
                <a href="{{ $activeVisits->previousPageUrl() }}#active" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                    Previous
                </a>
            @endif

            @foreach(range(1, $activeVisits->lastPage()) as $page)
                @if($page == $activeVisits->currentPage())
                    <button class="px-3 py-2 text-sm text-white rounded-md bg-[#07AF8B] hover:bg-[#007570]">
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $activeVisits->url($page) }}#active" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($activeVisits->hasMorePages())
                <a href="{{ $activeVisits->nextPageUrl() }}#active" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
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
