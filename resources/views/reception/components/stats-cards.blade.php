<div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <!-- Expected Today -->

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-lg font-medium text-gray-600">{{ __('Expected Today') }}</p>
                <p class="text-3xl font-bold text-[#FFCA00] mt-2" id="expected-today">{{ $expectedTodayCount }}</p>
            </div>
            <div class="w-12 h-12 bg-[#FFCA00]/10 rounded-xl flex items-center justify-center">

                <i class="fas fa-calendar-day text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Checked In -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-lg font-medium text-gray-600">{{ __('Checked In') }}</p>
                <p class="text-3xl font-bold text-green-600 mt-2" id="checked-in">{{ $checkedInCount }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sign-in-alt text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Checked Out -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-lg font-medium text-gray-600">{{ __('Checked Out') }}</p>
                <p class="text-3xl font-bold text-blue-600 mt-2" id="checked-out">{{ $checkedOutCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Cards -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-lg font-medium text-gray-600">{{ __('Active Cards') }}</p>
                <p class="text-3xl font-bold text-purple-600 mt-2" id="active-cards">{{ $cardsIssuedCount }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-id-badge text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>
