<div >
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl z-60 transform translate-x-full transition-transform duration-300">
        <div class="h-full overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="!bg-gradient-to-br !from-[#22807e] !to-[#00aa8c] text-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Profile Settings</h2>
                    <button onclick="toggleSidebar()" class="p-2 hover:bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- User Info -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span class="text-lg font-medium">{{ strtoupper(substr($fullName, 0, 2)) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $fullName }}</div>
                        <div class="text-sm opacity-90">Location: Abuja</div>
                        <div class="text-sm opacity-75">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Basic Information</h3>

                <form class="space-y-4">
                    <!-- Form fields would go here -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" value="{{ $fullName }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $staffEmail) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Update Profile Button -->
                    <button type="submit" class="w-full !bg-gradient-to-br !from-[#22807e] !to-[#00aa8c] text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity mt-6">
                        Update Profile
                    </button>
                </form>

                <!-- Action Buttons -->
                <div class="mt-8 space-y-3">
                    <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Change Password
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-100 text-red-700 py-3 rounded-lg font-medium hover:bg-red-200 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar.classList.contains('translate-x-full')) {
                    toggleSidebar();
                }
            }
        });

        // Close notifications when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const button = event.target.closest('button[onclick="toggleNotifications()"]');

            if (dropdown && !dropdown.contains(event.target) && !button) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</div>
