@extends('layouts.app')

@section('content')
<div class="container min-w-full" x-data="modalComponent()" @open-modal.window="openModal()">
    <!-- Header Section -->
    <div class="py-4 px-6 mb-6 my-6">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <img src="{{ asset('assets/logo-green-yellow.png') }}" alt={{__("Logo")}} class="h-10 md:h-12">

            <!-- Hamburger Icon for Mobile -->
            <button id={{__("menuToggle")}} class="md:hidden text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
                @include('partials.language_switcher')

                <a href="{{ route("profile.edit") }}"><span class="material-icons text-lg text-[#07AF8B]"">account_circle</span></a>

                <div class="text-right mt-4 md:mt-0">
                    <div><strong>{{ $staff->name }}</strong></div>
                    <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
                    @csrf
                    <button
                        class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Collapsible Menu -->
        <div id="mobileMenu" class="md:hidden mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
            @include('partials.language_switcher')

            <a href="{{ route("profile.update") }}"><span class="material-icons text-lg text-[#07AF8B]">account_circle</span></a>

            <div class="text-right mt-4 md:mt-0">
                <div><strong>{{ $staff->name }}</strong></div>
                <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
                @csrf
                <button
                    class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>

    <!-- top -->
    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="w-full md:min-h-[250px] rounded-xl flex flex-col lg:flex-row gap-6 bg-transparent md:bg-[#07AF8B]">
            <!-- Left Column -->
            <div class="w-full lg:w-2/3 space-y-6">
                <div class="bg-[#07AF8B] md:bg-transparent rounded-xl p-6 shadow-md md:shadow-none flex justify-center items-end min-h-[180px]">
                    <div class="text-white text-left max-w-full px-4">
                        <h2 class="text-3xl font-bold mb-2">{{__("Book visitors into Abuja AATC facilities")}}</h2>
                        <p class="text-lg mb-4 leading-relaxed">{{__("Request for entry permit for business associates and partners from the comfort of your office and get real-time notifications on the progress of your request.")}}</p>
                        <div class="block md:inline-block text-center w-full md:w-auto">
                            <button
                                @click="openModal()"
                                class="bg-[#FFCA00] hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg transition-colors w-full md:w-auto inline-block">
                                {{__("New Request")}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="w-full lg:w-1/3 md:flex md:items-center md:justify-center md:pr-6">
                {{-- STAT CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-[#FFCA00] text-black rounded-xl p-6 shadow-md text-center">
                        <h1 class="text-3xl font-bold">{{ $stats['total_requests'] ?? 0 }}</h1>
                        <p class="text-sm mt-2">{{ __('Total Requests') }}</p>
                    </div>
                    <div class="bg-[#07AF8B] md:bg-[#05896D] text-white rounded-xl p-6 shadow-md text-center">
                        <h1 class="text-3xl font-bold">{{ $stats['approved'] ?? 0 }}</h1>
                        <p class="text-sm mt-2">{{ __('Approved') }}</p>
                    </div>
                    <div class="bg-[#6c757d] text-white rounded-xl p-6 shadow-md text-center">
                        <h1 class="text-3xl font-bold">{{ $stats['declined'] ?? 0 }}</h1>
                        <p class="text-sm mt-2">{{ __('Declined') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bottom -->
    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Column -->
            <div class="w-full lg:w-1/2 space-y-6">
                {{-- NOTIFICATIONS --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">{{ __('Notifications') }}</h2>
                    @if ($notifications->isEmpty())
                        <div class="text-center text-gray-500 text-sm">
                            {{ __('No notifications yet.') }}
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($notifications as $note)
                                <li class="flex items-center justify-between gap-2 text-sm bg-gray-50 rounded-lg px-3 py-2">
                                    <div class="flex items-center gap-2 truncate flex-1 min-w-0">
                                        <span class="material-icons text-base
                                            {{ $note->status === 'approved' ? 'text-[#07AF8B]' : ($note->status === 'pending' ? 'text-[#FFA500]' : 'text-[#b00020]') }}">
                                            {{ $note->status === 'approved' ? 'check_circle' : ($note->status === 'pending' ? 'hourglass_empty' : 'cancel') }}
                                        </span>
                                        <span class="font-semibold truncate">{{ ucfirst(__($note->status)) }}</span>
                                    </div>
                                    <div class="truncate text-left text-gray-800 font-medium flex-1 min-w-0 px-2">
                                        {{ $note->visitor->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-gray-500 text-xs text-right truncate flex-1 min-w-0">
                                        {{ \Carbon\Carbon::parse($note->visit_date)->format('g:iA d/m/Y') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="container">
                {{-- SEARCH + REQUEST TABLE --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <h2 class="text-lg font-semibold">{{ __('My Requests') }}</h2>
                        <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                            <input type="text" name="search" class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="{{ __('Search visitors...') }}" value="{{ $search }}">
                            <div class="flex gap-2">
                                <button type="submit" class="bg-[#FFCA00] hover:bg-yellow-400 text-black font-bold px-4 py-2 rounded-lg text-sm">{{ __('Search') }}</button>
                                @if($search)
                                    <a href="{{ route('home') }}" class="text-[#07AF8B] text-sm">{{ __('Clear') }}</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-[#07AF8B] text-white">
                                    <th class="text-left p-3">{{ __('Visitor') }}</th>
                                    <th class="text-left p-3">{{ __('Date') }}</th>
                                    <th class="text-left p-3">{{ __('Purpose') }}</th>
                                    <th class="text-left p-3">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($requests as $request)
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="openModal({{ $request->id }})">
                                        <td class="p-3 truncate">{{ $request->visitor->name ?? 'N/A' }}</td>
                                        <td class="p-3">{{ \Carbon\Carbon::parse($request->visit_date)->format('d/m/Y g:i A') }}</td>
                                        <td class="p-3 truncate">{{ $request->reason }}</td>
                                        <td class="p-3">
                                            @php $status = strtolower($request->status); @endphp
                                            @if($status === 'approved')
                                                <span class="text-[#07AF8B] font-semibold">{{ __('Approved') }}</span>
                                            @elseif($status === 'pending')
                                                <span class="text-[#FFA500] font-semibold">{{ __('Pending') }}</span>
                                            @elseif ($status === 'denied')
                                                <span class="text-[#b00020] font-semibold">{{ __('Declined') }}</span>
                                            @else
                                                <span>{{ __($request->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-3 text-center text-gray-500">{{ __('No requests found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Request Modal -->
    <div x-show="isOpen"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

            <div
            {{-- @click.away="closeModal()" --}}
                 x-transition.scale.origin.center
                 class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">

                <!-- Card Top - Visitor Navigation -->
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Visitor <span x-text="currentVisitorIndex + 1"></span> of <span x-text="visitors.length"></span>
                    </h2>
                    <div class="flex items-center space-x-2">
                        <!-- Previous Visitor -->
                        <button
                            @click="prevVisitor()"
                            x-show="visitors.length > 1 && currentVisitorIndex > 0"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Next Visitor -->
                        <button
                            @click="nextVisitor()"
                            x-show="visitors.length > 1 && currentVisitorIndex < visitors.length - 1"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Close Modal -->
                        <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Card Body - Visitor Details -->
                <div class="p-6 space-y-4">
                    <!-- Email Lookup Section -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="flex space-x-2">
                            <input
                                x-model="currentVisitor.email"
                                @input="clearLookupStatus()"
                                type="email"
                                placeholder="john.doe@example.com"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <button
                                @click="lookupVisitor()"
                                :disabled="!currentVisitor.email || lookupLoading"
                                class="bg-[#007570] hover:bg-[#0b7671] disabled:bg-gray-50 disabled:text-gray-400 text-white px-4 py-2 rounded-md border border-gray-300 transition duration-200"
                            >
                                <span x-show="!lookupLoading">Look up</span>
                                <span x-show="lookupLoading">Searching..</span>
                            </button>
                        </div>
                    </div>

                    <!-- Lookup Status -->
                    <div x-show="lookupStatus" class="p-3 rounded-md flex items-center space-x-2"
                         :class="lookupFound ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200'">
                        <svg x-show="lookupFound" class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg x-show="!lookupFound" class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span :class="lookupFound ? 'text-green-800' : 'text-yellow-800'" x-text="lookupMessage"></span>
                    </div>

                    <!-- Visitor Details Form -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                            <input
                                x-model="currentVisitor.name"
                                type="text"
                                placeholder="John Doe"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                            <input
                                x-model="currentVisitor.phone"
                                type="tel"
                                placeholder="+1234567890"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization <span class="text-gray-400">(optional)</span></label>
                        <input
                            x-model="currentVisitor.organization"
                            type="text"
                            placeholder="Acme Inc."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Visit Information Section -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Visit Information</h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input
                                    x-model="currentVisitor.date"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Time of visit</label>
                                <input
                                    x-model="currentVisitor.time"
                                    type="time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Floor</label>
                            <select
                                x-model="currentVisitor.floor"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select floor</option>
                                <option value="Floor 1">Ground Floor</option>
                                <option value="Floor 2">MezerNine</option>
                                <option value="Floor 3">Floor 1</option>
                                <option value="Floor 4">Floor 2</option>
                                <option value="Floor 5">Floor 3</option>
                                <option value="Floor 3">Floor 4</option>
                                <option value="Floor 4">Floor 5</option>
                                <option value="Floor 5">Floor 6</option>
                                <option value="Floor 3">Floor 7</option>
                                <option value="Floor 4">Floor 8</option>
                                <option value="Floor 5">Floor 9</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                            <textarea
                                x-model="currentVisitor.reason"
                                rows="3"
                                placeholder="Purpose of visit..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card Bottom - Actions -->
                <div class="p-6 border-t bg-gray-50 space-y-3">
                    <!-- First Row - Add Visitor & Import CSV -->
                    <div class="flex space-x-3">
                        <button
                            @click="addNewVisitor()"
                            class="flex-1 bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md border border-gray-300 transition duration-200 flex items-center justify-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add visitor</span>
                        </button>

                        <button
                            @click="importCSV()"
                            class="flex-1 bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md border border-gray-300 transition duration-200 flex items-center justify-center space-x-2"
                        >
                            {{-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg> --}}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 0l-7 7m7-7l7 7"></path>
                            </svg>

                            <span>Upload CSV</span>
                        </button>
                    </div>
                    <span @click="downloadCSVTemplate"
                    className="text-sm text-gray-500 px-6 text-right ml-auto cursor-pointer ">
                        CSV Template
                </span>
                    {{-- <span
                        @click="downloadCSVTemplate"
                        class="flex-1 bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md border border-gray-300 transition duration-200 flex items-center justify-center space-x-2"
                    >
                        <span>Download CSV Template</span>
                    </span> --}}


                    <!-- Second Row - Submit Button -->
                    <button
                        @click="submitVisitors()"
                        class="w-full bg-[#007570] hover:bg-[#0b7671] text-white font-semibold py-3 px-4 rounded-md transition duration-200"
                    >
                        Submit Request
                    </button>
                </div>
            </div>
    </div>

    <div x-show="showCsvErrorModal" x-transition x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full space-y-4">
            <h2 class="text-lg font-semibold text-red-600">CSV Upload Errors</h2>
            <ul class="list-disc pl-5 text-sm text-gray-800 max-h-60 overflow-y-auto">
                <template x-for="error in csvErrors" :key="error">
                    <li x-text="error"></li>
                </template>
            </ul>
            <div class="text-right">
                <button
                    @click="showCsvErrorModal = false"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md"
                >
                    Close
                </button>
            </div>
        </div>
    </div>

</div>

<script>
        function modalComponent() {
            return {
                isOpen: false,
                visitors: [],
                currentVisitorIndex: 0,

                showCsvErrorModal: false,
                csvErrors: [],

                lookupLoading: false,
                lookupStatus: false,
                lookupFound: false,
                lookupMessage: '',

                get currentVisitor() {
                    return this.visitors[this.currentVisitorIndex] || this.createEmptyVisitor();
                },

                set currentVisitor(value) {
                    if (this.visitors[this.currentVisitorIndex]) {
                        this.visitors[this.currentVisitorIndex] = value;
                    }
                },

                createEmptyVisitor() {
                    return {
                        email: '',
                        name: '',
                        phone: '',
                        organization: '',
                        hostName: '',
                        visitTime: '14:30',
                        floor: 'Floor 3',
                        reason: ''
                    };
                },

                openModal() {
                    this.isOpen = true;
                    if (this.visitors.length === 0) {
                        this.visitors = [this.createEmptyVisitor()];
                    }
                    this.currentVisitorIndex = 0;
                },

                closeModal() {
                    this.isOpen = false;
                    this.visitors = [];
                    this.currentVisitorIndex = 0;
                    this.clearLookupStatus();
                },

                nextVisitor() {
                    if (this.currentVisitorIndex < this.visitors.length - 1) {
                        this.currentVisitorIndex++;
                        this.clearLookupStatus();
                    }
                },

                prevVisitor() {
                    if (this.currentVisitorIndex > 0) {
                        this.currentVisitorIndex--;
                        this.clearLookupStatus();
                    }
                },

                addNewVisitor() {
                    this.visitors.push(this.createEmptyVisitor());
                    this.currentVisitorIndex = this.visitors.length - 1;
                    this.clearLookupStatus();
                },

                async lookupVisitor() {
                    if (!this.currentVisitor.email) return;

                    this.lookupLoading = true;
                    this.clearLookupStatus();

                    try {
                        const response = await fetch('/visitor-lookup', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ email: this.currentVisitor.email })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to lookup visitor');
                        }

                        const data = await response.json();

                        this.lookupStatus = true;
                        this.lookupFound = data.found;

                        if (data.found) {
                            this.lookupMessage = 'Visitor found';
                            // Auto-fill visitor details
                            this.visitors[this.currentVisitorIndex] = {
                                ...this.currentVisitor,
                                name: data.visitor.name,
                                phone: data.visitor.phone,
                                organization: data.visitor.organization
                            };
                        } else {
                            this.lookupMessage = 'Not found, supply visitor details';
                        }
                    } catch (error) {
                        this.lookupStatus = true;
                        this.lookupFound = false;
                        this.lookupMessage = 'Error looking up visitor';
                        console.error('Lookup error:', error);
                    } finally {
                        this.lookupLoading = false;
                    }
                },

                clearLookupStatus() {
                    this.lookupStatus = false;
                    this.lookupFound = false;
                    this.lookupMessage = '';
                },

                // importCSV() {
                //     // Create file input dynamically
                //     const input = document.createElement('input');
                //     input.type = 'file';
                //     input.accept = '.csv';
                //     input.onchange = (e) => {
                //         const file = e.target.files[0];
                //         if (file) {
                //             // For now, simulate CSV import with dummy data
                //             this.visitors = [
                //                 {
                //                     email: 'import1@example.com',
                //                     name: 'Import User 1',
                //                     phone: '+1111111111',
                //                     organization: 'CSV Corp',
                //                     hostName: 'Manager',
                //                     visitTime: '09:00',
                //                     floor: 'Floor 2',
                //                     reason: 'Imported from CSV'
                //                 },
                //                 {
                //                     email: 'import2@example.com',
                //                     name: 'Import User 2',
                //                     phone: '+2222222222',
                //                     organization: 'Data Inc',
                //                     hostName: 'Director',
                //                     visitTime: '10:30',
                //                     floor: 'Floor 4',
                //                     reason: 'CSV Import Meeting'
                //                 }
                //             ];
                //             this.currentVisitorIndex = 0;
                //             this.clearLookupStatus();
                //         }
                //     };
                //     input.click();
                // },

//                 importCSV() {
//     const input = document.createElement('input');
//     input.type = 'file';
//     input.accept = '.csv';

//     input.onchange = async (e) => {
//         const file = e.target.files[0];
//         if (!file) return;

//         Papa.parse(file, {
//             header: true,
//             skipEmptyLines: true,
//             complete: async (results) => {
//                 const rows = results.data;
//                 const validRows = [];
//                 const errors = [];

//                 rows.forEach((row, index) => {
//                     const rowNum = index + 2; // header is row 1
//                     const required = ['email', 'name', 'date', 'time', 'floor'];
//                     const missingFields = required.filter(f => !row[f] || row[f].trim() === '');

//                     if (missingFields.length > 0) {
//                         errors.push(`Row ${rowNum}: Missing ${missingFields.join(', ')}`);
//                         return;
//                     }

//                     validRows.push({
//                         email: row.email.trim(),
//                         name: row.name.trim(),
//                         phone: row.phone?.trim() || '',
//                         organization: row.organization?.trim() || '',
//                         date: row.date.trim(),
//                         time: row.time.trim(),
//                         floor: row.floor.trim(),
//                         reason: row.reason?.trim() || ''
//                     });
//                 });

//                 if (errors.length > 0) {
//                     this.csvErrors = errors;
//                     this.showCsvErrorModal = true;
//                     return;
//                 }

//                 try {
//                     const response = await fetch('/store-visitors', {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                         },
//                         body: JSON.stringify({ visitors: validRows })
//                     });

//                     const data = await response.json();

//                     if (response.ok) {
//                         this.visitors = validRows;
//                         this.currentVisitorIndex = 0;
//                         this.clearLookupStatus();
//                         this.csvErrors = [];
//                     } else if (response.status === 422) {
//                         this.csvErrors = Object.entries(data.errors || {}).map(
//                             ([key, messages]) => `${key}: ${messages.join(', ')}`
//                         );
//                         this.showCsvErrorModal = true;
//                     } else {
//                         alert(data.message || 'An error occurred.');
//                     }
//                 } catch (error) {
//                     console.log(error)
//                     alert('Failed to submit CSV data.');
//                 }
//             }
//         });
//     };

//     input.click();
// },


                importCSV() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.csv';
    input.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('file', file);

            fetch('/upload-visitors-csv', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.visitors = data.visitors;
                    this.currentVisitorIndex = 0;
                    this.clearLookupStatus();
                    alert('CSV uploaded successfully!');
                } else {
                    // alert(data.message || 'Upload failed.');
                    this.csvErrors = Object.entries(data.errors || {}).map(
                            ([key, messages]) => `${key}: ${messages.join(', ')}`
                        );
                        this.closeModal();
                        this.showCsvErrorModal = true;
                }
            })
            .catch(err => {
                alert('An error occurred while uploading.');
                console.error(err);
            });
        }
    };
    input.click();
},

downloadCSVTemplate() {
    const csvContent = `email,name,phone,organization,date,time,floor,reason
john@example.com,John Doe,+1234567890,Tech Co,"2025-06-15",14:00,Floor 1,Demo Presentation
jane@example.com,Jane Smith,+0987654321,Design Inc,"2025-06-16",15:30,Floor 2,Client Meeting`;

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.setAttribute('download', 'visitor_template.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
},


                submitVisitors: async function () {
                    const visitorsArray = Alpine.raw(this.visitors)
                    const bodyValue = JSON.stringify({ visitors: visitorsArray })
                    console.log({ visitors: visitorsArray })
                    try {
                        const response = await fetch('/submit-visitors', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },

                            body: bodyValue
                            // body: JSON.stringify({ visitors: JSON.parse(JSON.stringify(Alpine.raw(this.visitors))) })
                        });

                        const result = await response.json();

                        if (result.success) {
                            // alert('Visitors submitted successfully!');
                            this.closeModal();
                        } else {
                            alert(result.message || 'Something went wrong.');
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                        alert('An error occurred while submitting visitors.');
                    }
                }

            }
        }
    </script>

<script>
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

@endsection

{{-- @extends('layouts.app')

@section('content')
<div class="container min-w-full">
    <!-- Header Section -->
<div class="py-4 px-6 mb-6 my-6 ">
    <div class="flex items-center justify-between">
        <!-- Logo -->
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt={{__("Logo")}} class="h-10 md:h-12">

        <!-- Hamburger Icon for Mobile -->
        <button id={{__("menuToggle")}} class="md:hidden text-gray-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
            @include('partials.language_switcher')

            <a href="{{ route("profile.edit") }}"><span class="material-icons text-lg text-[#07AF8B]"">account_circle</span></a>

            <div class="text-right mt-4 md:mt-0">
                <div><strong>{{ $staff->name }}</strong></div>
                <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
                @csrf
                <button
                    class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Collapsible Menu -->
    <div id="mobileMenu" class="md:hidden mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
        @include('partials.language_switcher')

        <a href="{{ route("profile.update") }}"><span class="material-icons text-lg text-[#07AF8B]">account_circle</span></a>

        <div class="text-right mt-4 md:mt-0">
            <div><strong>{{ $staff->name }}</strong></div>
            <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
            @csrf
            <button
                class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>


    <!-- top -->

    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="w-full md:min-h-[250px] rounded-xl flex flex-col lg:flex-row gap-6 bg-transparent md:bg-[#07AF8B]">
          <!-- Left Column -->
          <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-[#07AF8B] md:bg-transparent rounded-xl  p-6 shadow-md md:shadow-none flex justify-center items-end min-h-[180px]">
                <div class="text-white text-left max-w-full px-4">
                  <h2 class="text-3xl font-bold mb-2">{{__("Book visitors into Abuja AATC facilities")}}</h2>
                  <p class="text-lg mb-4 leading-relaxed">{{__("Request for entry permit for business associates and partners from the comfort of your office and get real-time notifications on the progress of your request.")}}</p>
                  <div class="block md:inline-block text-center w-full md:w-auto ">
                    <a href="{{ route('register_visitor') }}" class="bg-[#FFCA00] hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg transition-colors w-full md:w-auto inline-block">
                        {{__("New Request")}}
                    </a>
                  </div>
                </div>
              </div>
          </div>

          <!-- Right Column -->
          <div class="w-full lg:w-1/3 md:flex md:items-center md:justify-center md:pr-6">
            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-[#FFCA00] text-black rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['total_requests'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Total Requests') }}</p>
                </div>
                <div class="bg-[#07AF8B] md:bg-[#05896D] text-white rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['approved'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Approved') }}</p>
                </div>
                <div class="bg-[#6c757d] text-white rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['declined'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Declined') }}</p>
                </div>
              </div>
            </div>

          </div>
      </div>

    <!-- bottom -->

    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Left Column -->
          <div class="w-full lg:w-1/2 space-y-6">

            <!-- NOTIFICATIONS -->
            <div class="bg-white rounded-xl shadow-md p-6">
              <h2 class="text-lg font-semibold mb-4">{{ __('Notifications') }}</h2>
              @if ($notifications->isEmpty())
    <div class="text-center text-gray-500 text-sm">
      {{ __('No notifications yet.') }}
    </div>
  @else
  <ul class="space-y-3">
    @foreach ($notifications as $note)
      <li class="flex items-center justify-between gap-2 text-sm bg-gray-50 rounded-lg px-3 py-2">

        <div class="flex items-center gap-2 truncate flex-1 min-w-0">
          <span class="material-icons text-base
            {{ $note->status === 'approved' ? 'text-[#07AF8B]' : ($note->status === 'pending' ? 'text-[#FFA500]' : 'text-[#b00020]') }}">
            {{ $note->status === 'approved' ? 'check_circle' : ($note->status === 'pending' ? 'hourglass_empty' : 'cancel') }}
          </span>
          <span class="font-semibold truncate">{{ ucfirst(__($note->status)) }}</span>
        </div>

        <div class="truncate text-left text-gray-800 font-medium flex-1 min-w-0 px-2">
          {{ $note->visitor->name ?? 'Unknown' }}
        </div>

        <div class="text-gray-500 text-xs text-right truncate flex-1 min-w-0">
          {{ \Carbon\Carbon::parse($note->visit_date)->format('g:iA d/m/Y') }}
        </div>
      </li>
    @endforeach
</ul>
  @endif
            </div>
          </div>

          <!-- Right Column -->
          <div class="container">

            <!-- SEARCH + REQUEST TABLE -->
            <div class="bg-white rounded-xl shadow-md p-6">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h2 class="text-lg font-semibold">{{ __('My Requests') }}</h2>
                <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                  <input type="text" name="search" class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="{{ __('Search visitors...') }}" value="{{ $search }}">
                  <div class="flex gap-2">
                    <button type="submit" class="bg-[#FFCA00] hover:bg-yellow-400 text-black font-bold px-4 py-2 rounded-lg text-sm">{{ __('Search') }}</button>
                    @if($search)
                      <a href="{{ route('home') }}" class="text-[#07AF8B] text-sm">{{ __('Clear') }}</a>
                    @endif
                  </div>
                </form>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="bg-[#07AF8B] text-white">
                      <th class="text-left p-3">{{ __('Visitor') }}</th>
                      <th class="text-left p-3">{{ __('Date') }}</th>
                      <th class="text-left p-3">{{ __('Purpose') }}</th>
                      <th class="text-left p-3">{{ __('Status') }}</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse ($requests as $request)
                      <tr class="hover:bg-gray-50 cursor-pointer" onclick="openModal({{ $request->id }})">
                        <td class="p-3 truncate">{{ $request->visitor->name ?? 'N/A' }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($request->visit_date)->format('d/m/Y g:i A') }}</td>
                        <td class="p-3 truncate">{{ $request->reason }}</td>
                        <td class="p-3">
                          @php $status = strtolower($request->status); @endphp
                          @if($status === 'approved')
                            <span class="text-[#07AF8B] font-semibold">{{ __('Approved') }}</span>
                          @elseif($status === 'pending')
                            <span class="text-[#FFA500] font-semibold">{{ __('Pending') }}</span>
                          @elseif ($status === 'denied')
                            <span class="text-[#b00020] font-semibold">{{ __('Declined') }}</span>
                          @else
                            <span>{{ __($request->status) }}</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">{{ __('No requests found.') }}</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        </div>
      </div>

</div>
<script>
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

@endsection --}}
