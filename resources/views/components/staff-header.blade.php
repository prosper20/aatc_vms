<header class="bg-white text-gray-900 shadow-lg sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-3 lg:py-6 md:py-4">
            <div class="flex items-center space-x-3 md:space-x-4">
                <div class="flex-shrink-0">
                    <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="Logo" class="h-10 w-auto md:h-12">
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-lg md:text-2xl font-semibold leading-tight">Abuja AATC-VMS</h1>
                    <p class="text-xs md:text-[14px] opacity-90">Visitor Management System</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Language Dropdown (Desktop only) -->
                <div class="hidden md:flex items-center space-x-2">
                    @include('partials.language_switcher')
                </div>

                <!-- Notification Icon -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="p-2 rounded-full bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-yellow">
                        <svg class="w-6 h-6 text-[#00aa8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span> --}}
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown"
                        class="hidden
                               fixed md:absolute
                               inset-0 md:inset-auto
                               md:right-0 md:mt-2
                               w-full md:w-96
                               h-full md:h-auto
                               bg-white
                               md:rounded-xl
                               md:shadow-lg
                               z-50
                               transition-all duration-300 ease-in-out">

                        <!-- Mobile Header with Close Button -->
                        <div class="flex md:hidden items-center justify-between p-4 border-b bg-white">
                            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                            <button onclick="toggleNotifications()" class="p-2 rounded-full hover:bg-gray-100">
                                <svg class="w-6 h-6 text-[#00aa8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Desktop Header -->
                        <div class="hidden md:block p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                        </div>

                        <!-- Notifications Content -->
                        <div class="p-4 space-y-3 h-full md:h-auto md:max-h-96 overflow-y-auto">
                            <!-- Dynamic notifications would go here -->

                            <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full flex-shrink-0"></div>
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-700">Pending</div>
                                    <div class="text-sm text-gray-600">Prosper Bobson</div>
                                </div>
                                <div class="text-xs text-gray-500">8:58PM 06/07/2025</div>
                            </div>

                            <!-- Add more sample notifications for demonstration -->
                            <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-blue-400 rounded-full flex-shrink-0"></div>
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-700">Completed</div>
                                    <div class="text-sm text-gray-600">John Smith</div>
                                </div>
                                <div class="text-xs text-gray-500">7:30PM 06/07/2025</div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-green-400 rounded-full flex-shrink-0"></div>
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-700">Approved</div>
                                    <div class="text-sm text-gray-600">Mary Johnson</div>
                                </div>
                                <div class="text-xs text-gray-500">6:15PM 06/07/2025</div>
                            </div>

                            <!-- More notifications... -->
                        </div>

                        <!-- Footer -->
                        <div class="p-3 border-t text-center bg-white md:bg-transparent">
                            <a href="#" class="text-sm text-teal-600 hover:underline">View all notifications</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 hidden md:flex">
                    <div class="text-right">
                        <p class="text-xs md:text-[14px]">{{ $fullName }}</p>
                        <p class="text-xs md:text-[14px]">Location: Abuja</p>
                    </div>
                    <button onclick="toggleSidebar()" class="h-8 w-8 rounded-full bg-yellow-50 hover:bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-user text-[#00aa8c]"></i>
                    </button>
                </div>

                <!-- Hamburger Menu (Mobile) -->
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-yellow-50">
                    <svg class="w-6 h-6 text-[#00aa8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
