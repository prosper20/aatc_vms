<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Modal Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6" x-data="modalComponent()">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Laravel Modal Demo</h1>
            <p class="text-gray-600 mb-6 text-center">Click the button below to open a modal with database lookup and conditional UI rendering.</p>

            <div class="text-center">
                <button
                    @click="openModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                >
                    Add Visitor Requests
                </button>
            </div>
        </div>

        <!-- Modal Component -->
        <div x-show="isOpen"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

            <div @click.away="closeModal()"
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

                {{-- <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800" x-text="`Visitor ${currentVisitorIndex + 1}`"></h2>
                    <div class="flex items-center space-x-2">
                        <button
                            @click="nextVisitor()"
                            x-show="visitors.length > 1 && currentVisitorIndex < visitors.length - 1"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div> --}}

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
                                    x-model="currentVisitor.hostName"
                                    type="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Time of visit</label>
                                <input
                                    x-model="currentVisitor.visitTime"
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

                    <!-- Second Row - Submit Button -->
                    <button
                        class="w-full bg-[#007570] hover:bg-[#0b7671] text-white font-semibold py-3 px-4 rounded-md transition duration-200"
                    >
                        Submit Request
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

                importCSV() {
                    // Create file input dynamically
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = '.csv';
                    input.onchange = (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            // For now, simulate CSV import with dummy data
                            this.visitors = [
                                {
                                    email: 'import1@example.com',
                                    name: 'Import User 1',
                                    phone: '+1111111111',
                                    organization: 'CSV Corp',
                                    hostName: 'Manager',
                                    visitTime: '09:00',
                                    floor: 'Floor 2',
                                    reason: 'Imported from CSV'
                                },
                                {
                                    email: 'import2@example.com',
                                    name: 'Import User 2',
                                    phone: '+2222222222',
                                    organization: 'Data Inc',
                                    hostName: 'Director',
                                    visitTime: '10:30',
                                    floor: 'Floor 4',
                                    reason: 'CSV Import Meeting'
                                }
                            ];
                            this.currentVisitorIndex = 0;
                            this.clearLookupStatus();
                        }
                    };
                    input.click();
                }
            }
        }
    </script>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Modal Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6" x-data="modalComponent()">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Laravel Modal Demo</h1>
            <p class="text-gray-600 mb-6 text-center">Click the button below to open a modal with database lookup and conditional UI rendering.</p>

            <div class="text-center">
                <button
                    @click="openModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                >
                    Open User Modal
                </button>
            </div>
        </div>

        <!-- Modal Component -->
        <div x-show="isOpen"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

            <div @click.away="closeModal()"
                 x-transition.scale.origin.center
                 class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">

                <!-- Modal Header -->
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">User Management</h2>
                    <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Search Section -->
                    <div class="mb-4">
                        <input
                            x-model="searchTerm"
                            @input="searchUsers()"
                            type="text"
                            placeholder="Search users..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <p class="mt-2 text-gray-600">Loading users...</p>
                    </div>

                    <!-- Error State -->
                    <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <p x-text="error"></p>
                    </div>

                    <!-- Stats Section - Conditional Rendering -->
                    <div x-show="data.stats && !loading" class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-3 bg-blue-50 rounded">
                            <div x-text="data.stats?.total || 0" class="text-2xl font-bold text-blue-600"></div>
                            <div class="text-sm text-gray-600">Total</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded">
                            <div x-text="data.stats?.active || 0" class="text-2xl font-bold text-green-600"></div>
                            <div class="text-sm text-gray-600">Active</div>
                        </div>
                        <div class="text-center p-3 bg-red-50 rounded">
                            <div x-text="data.stats?.inactive || 0" class="text-2xl font-bold text-red-600"></div>
                            <div class="text-sm text-gray-600">Inactive</div>
                        </div>
                    </div>

                    <!-- Users List - Conditional Rendering -->
                    <div x-show="data.users && data.users.length > 0 && !loading">
                        <h3 class="text-lg font-semibold mb-3">Users</h3>
                        <div class="space-y-3">
                            <template x-for="user in (data.users || [])" :key="user.id">
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 x-text="user.name" class="font-medium text-gray-800"></h4>
                                            <p x-text="user.email" class="text-gray-600 text-sm"></p>
                                        </div>
                                        <span
                                            x-text="user.status"
                                            :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 py-1 rounded-full text-xs font-medium"
                                        ></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div x-show="data.users && data.users.length === 0 && !loading" class="text-center py-8">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600" x-text="data.message || 'No users found'"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-6 border-t bg-gray-50">
                    <button
                        @click="closeModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-200"
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
                loading: false,
                error: null,
                searchTerm: '',
                data: {
                    users: [],
                    stats: { total: 0, active: 0, inactive: 0 },
                    message: ''
                },
                searchTimeout: null,

                openModal() {
                    console.log('Opening modal...');
                    this.isOpen = true;
                    this.loadUsers();
                },

                closeModal() {
                    this.isOpen = false;
                    this.data = {
                        users: [],
                        stats: { total: 0, active: 0, inactive: 0 },
                        message: ''
                    };
                    this.searchTerm = '';
                    this.error = null;
                },

                async loadUsers(search = '') {
                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch('/modal-data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ search: search })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to load users');
                        }

                        this.data = await response.json();
                    } catch (error) {
                        this.error = 'Failed to load users. Please try again.';
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                searchUsers() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.loadUsers(this.searchTerm);
                    }, 300);
                }
            }
        }
    </script>
</body>
</html> --}}
