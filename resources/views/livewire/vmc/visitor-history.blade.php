<div>
    <!-- Search Input -->
    <div class="mb-4 flex flex-col sm:flex-row w-full items-stretch sm:items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative w-full sm:w-96 flex-grow sm:flex-grow-0">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors duration-150"
                   placeholder="Search visitor history..."
                   aria-label="Search visitor history"
                   value="{{ $search }}">

            <!-- Clear search button -->
            @if($search)
                <button wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>

        <!-- Export Button -->
        <button onclick="openModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium bg-[#FFCA00] hover:bg-[#e0b200] rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFCA00]"
                aria-label="Export data">
            <i class="fas fa-download mr-2"></i>
            Export Data
        </button>
    </div>

    <!-- History Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left shadow-sm">
            <thead class="bg-gray-100">
                <tr class="text-xs text-gray-600 uppercase tracking-wider">
                    <th class="px-4 py-3">Visitor</th>
                    <th class="px-4 py-3">Organization</th>
                    <th class="px-4 py-3">Reason</th>
                    <th class="px-4 py-3">Floor</th>
                    <th class="px-4 py-3">Visit Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($history as $visit)
                    <tr wire:key="visit-{{ $visit->id }}">
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $visit->visitor->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $visit->visitor->email ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $visit->visitor->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $visit->visitor->organization ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $visit->reason ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $visit->floor_of_visit ?? '-' }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}
                            <br>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($visit->visit_time)->format('h:i A') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'approved' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'denied' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses[$visit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($visit->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('reception.visits.show', $visit->id) }}"
                               class="text-[#FFCA00] hover:text-[#e0b200] transition-colors duration-200">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-400">
                            @if($search)
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-search text-2xl mb-2"></i>
                                    <p>No visitor history found matching "<strong>{{ $search }}</strong>"</p>
                                    <button wire:click="$set('search', '')"
                                            class="mt-2 text-teal-600 hover:text-teal-800 text-sm underline">
                                        Clear search
                                    </button>
                                </div>
                            @else
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-history text-2xl mb-2"></i>
                                    <p>No visitor history found</p>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($history->hasPages())
            <div class="mt-4">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>

