<!-- Invite Guest Tab -->
<div id="invite-tab" class="tab-content">
    <div class="max-w-7xl">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Invite Guests</h2>
            <p class="text-gray-600">Fill out the form below to send invitations to your guests.</p>
        </div>

        <form id="invite-form" class="space-y-8">
            @csrf
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
                                <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="guest_name" id="guest_name" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="Enter guest's full name">
                            </div>

                            <div class="mb-6">
                                <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="guest_email" id="guest_email" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="guest@example.com">
                            </div>

                            <div class="mb-6">
                                <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="guest_phone" id="guest_phone" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="+1 (555) 123-4567">
                            </div>

                            <div class="mb-6">
                                <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">Organization (Optional)</label>
                                <input type="text" name="organization" id="organization"
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       placeholder="Company name">
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
                            <div class="mb-6 sm:col-span-2">
                                <label for="visit_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                                <textarea name="visit_reason" id="visit_reason" rows="3" required
                                          class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                          placeholder="Describe the purpose of the visit"></textarea>
                            </div>

                            <div class="mb-6">
                                <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">Visit Date *</label>
                                <input type="date" name="visit_date" id="visit_date" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white"
                                       min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-6">
                                <label for="visit_time" class="block text-sm font-medium text-gray-700 mb-2">Visit Time *</label>
                                <input type="time" name="visit_time" id="visit_time" required
                                       class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                            </div>

                            <div class="mb-6 sm:col-span-2">
                                <label for="floor" class="block text-sm font-medium text-gray-700 mb-2">Floor/Department *</label>
                                <select name="floor" id="floor" required
                                        class="w-full py-3 px-4 border border-gray-300 rounded-xl bg-slate-50 focus:outline-none focus:border-emerald-300 focus:bg-white">
                                    <option value="">Select floor</option>
                                    <option value="ground">Ground Floor</option>
                                    <option value="mezzanine">Mezzanine</option>
                                    <option value="1st">Floor 1</option>
                                    <option value="2nd">Floor 2</option>
                                    <option value="3rd">Floor 3</option>
                                    <option value="4th">Floor 4</option>
                                    <option value="5th">Floor 5</option>
                                    <option value="6th">Floor 6</option>
                                    <option value="7th">Floor 7</option>
                                    <option value="8th">Floor 8</option>
                                    <option value="9th">Floor 9</option>
                                </select>
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
                            <span class="text-sm text-gray-500" id="guest-count">1 Guest</span>
                        </div>

                        <div id="guest-navigation" class="flex items-center justify-between mb-4 hidden">
                            <button type="button" id="prev-guest"
                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <span class="text-sm font-medium" id="guest-indicator">Guest 1 of 1</span>

                            <button type="button" id="next-guest"
                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        <div class="space-y-2">
                            <button type="button" id="add-guest"
                                    class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-plus mr-2"></i>
                                Add Another Guest
                            </button>

                            <button type="button" id="remove-guest"
                                    class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 text-red-600 hover:text-red-700 hover:bg-red-50 hidden">
                                Remove Current Guest
                            </button>
                        </div>
                    </div>

                    <!-- CSV Import/Export -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">CSV Import/Export</h3>
                        <div class="space-y-2">
                            <div class="relative">
                                <input type="file" accept=".csv" id="csv-import" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <button type="button"
                                class="w-full px-4 py-3 rounded-xl text-white text-base font-medium transition-all duration-200 bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import Guests from CSV
                                </button>
                            </div>
                            <button type="button" id="download-template"
                            class="w-full px-4 py-3 rounded-xl text-base font-medium border border-gray-300 hover:bg-gray-50 transition-all duration-200 active:translate-y-0 hover:-translate-y-px cursor-pointer">
                                <i class="fas fa-download mr-2"></i>
                                Download CSV Template
                            </button>


                        </div>
                    </div>

                    <!-- Current Guest Summary -->
                    <div class="hidden bg-blue-50 p-4 rounded-lg border border-green-200">
                        <h4 class="text-sm font-medium text-green-900 mb-2">Current Guest Summary</h4>
                        <div class="text-sm text-green-700 space-y-1" id="guest-summary">
                            <p><strong>Name:</strong> <span id="summary-name">Not specified</span></p>
                            <p><strong>Email:</strong> <span id="summary-email">Not specified</span></p>
                            <p><strong>Date:</strong> <span id="summary-date">Not specified</span></p>
                            <p><strong>Floor:</strong> <span id="summary-floor">Not specified</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <button type="submit" id="submit-btn"
                class="px-4 py-3 rounded-xl text-white text-base font-medium flex items-center justify-center transition-all duration-200 bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer">
                Send All Invitations (<span id="submit-count">1</span>)
                </button>

            </div>
        </form>
    </div>

    <!-- Hidden template for additional guests -->
    <template id="guest-form-template">
        <div class="guest-form-data" style="display: none;">
            <input type="text" class="guest-name" name="guests[][name]">
            <input type="email" class="guest-email" name="guests[][email]">
            <input type="tel" class="guest-phone" name="guests[][phone]">
            <input type="text" class="guest-organization" name="guests[][organization]">
            <textarea class="guest-reason" name="guests[][reason]"></textarea>
            <input type="date" class="guest-date" name="guests[][date]">
            <input type="time" class="guest-time" name="guests[][time]">
            <input type="text" class="guest-floor" name="guests[][floor]">
        </div>
    </template>

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50"></div>
</div>
