<div class="w-full flex flex-col items-center justify-center">
    <!-- Visitor Information (reusing existing partial) -->
    {{-- @include('gate.partials.visitor-info') --}}
    <div x-show="vehicleData" class="complete-section">
        <!-- Header -->
        <div class="px-6 pt-6">
            <div class="flex items-center gap-2">
                <template x-if="visitorData && visitorData.verificationPassed">
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                </template>
                <template x-if="visitorData && !visitorData.verificationPassed">
                    <i class="fas fa-times-circle text-red-500 text-lg"></i>
                </template>
                <span class="px-3 py-1 rounded-full text-xs font-medium"
                      :class="visitorData && visitorData.verificationPassed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      x-text="visitorData && visitorData.verificationPassed ? 'Verified Successfully' : 'Verification Failed'">
                </span>
            </div>
            <div class="p-3 rounded-lg text-sm"
                 :class="visitorData && visitorData.verificationPassed ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'"
                 x-text="visitorData ? visitorData.verificationMessage : ''">
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Visitor Information</h3>
            <div class="space-y-3" x-show="visitorData">
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Name:</span>
                    <span class="text-right" x-text="visitorData ? visitorData.visitor_name : ''"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Status:</span>
                    <span class="text-right" x-text="visitorData ? visitorData.status : ''"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Host:</span>
                    <span class="text-right" x-text="visitorData ? visitorData.host_name : ''"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Purpose:</span>
                    <span class="text-right" x-text="visitorData ? visitorData.purpose : ''"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Scheduled:</span>
                    <span class="text-right" x-text="visitorData ? visitorData.scheduled_date : ''"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mode of Arrival -->
    {{-- <div x-show="vehicleData" class="w-full max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Mode of Arrival</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Mode:</span>
                <span class="capitalize" x-text="vehicleData.modeOfArrival"></span>
            </div>
            <template x-if="vehicleData.modeOfArrival === 'vehicle'">
                <div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Plate Number:</span>
                        <span class="font-mono" x-text="vehicleData.plateNumber"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="capitalize" x-text="vehicleData.vehicleType"></span>
                    </div>
                </div>
            </template>
        </div>
    </div> --}}

    <div x-show="vehicleData" class="complete-section">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Mode of Arrival</h3>
            <div class="space-y-3" x-show="visitorData">
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600">Mode:</span>
                    <span class="text-right capitalize" x-text="visitorData ? vehicleData.modeOfArrival : ''"></span>
                </div>
                <template x-if="vehicleData.modeOfArrival === 'vehicle'">
                    <div>
                        {{-- <div class="flex justify-between">
                            <span class="text-gray-600">Plate Number:</span>
                            <span class="font-mono" x-text="vehicleData.plateNumber"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="capitalize" x-text="vehicleData.vehicleType"></span>
                        </div> --}}
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Plate Number:</span>
                            <span class="text-right" x-text="visitorData ? vehicleData.plateNumber : ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Type:</span>
                            <span class="text-right capitalize" x-text="visitorData ? vehicleData.vehicleType : ''"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Gate Operative Selection -->
    <div class="w-full max-w-md mx-auto mb-[1.5rem]">
        <label class="text-lg font-semibold block mb-2">Verified By</label>
        <div class="input-field">
            <select
            x-model="verifiedBy"
            {{-- class="w-full px-4 py-3 border border-gray-300 rounded-xl text-[0.9375rem] bg-[#f8fafc] transition-all duration-200 focus:outline-none focus:border-[#d3f2ee] focus:bg-white" --}}
        >
            <option value="">Select operative</option>
            <template x-for="operative in operatives" :key="operative.id">
                <option :value="operative.id" x-text="operative.name"></option>
            </template>
        </select>
        </div>
    </div>
    {{-- <div class="w-full max-w-md mx-auto">
        <label class="text-sm font-medium block mb-2">Verified By</label>
        <select x-model="verifiedBy"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select gate operative</option>
            <template x-for="operative in operatives" :key="operative.id">
                <option :value="operative.id" x-text="operative.name"></option>
            </template>
        </select>
    </div> --}}

    <!-- Action Buttons -->
    <div class="flex gap-3 max-w-md w-full">
        <!-- Check-In Button -->
        {{-- <button @click="handleCheckInVisitor()"
                :disabled="!verifiedBy || isLoading"
                :class="{
                    'bg-gray-400 cursor-not-allowed': !verifiedBy || isLoading,
                    'bg-green-600 hover:bg-green-700': verifiedBy && !isLoading
                }"
                class="flex-1 px-4 py-3 text-white rounded-md font-medium transition-colors">
            <span x-text="isLoading ? 'Finishing...' : 'Complete'"></span>
        </button> --}}
        <button
    @click="handleCheckInVisitor()"
    :disabled="!verifiedBy || isLoading"
    :class="[
        'w-full px-4 py-4 rounded-xl text-white text-base font-medium flex items-center justify-center gap-2 transition-all duration-200',
        (!verifiedBy || isLoading)
            ? 'bg-gray-400 cursor-not-allowed'
            : 'bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer'
    ]"
>
    <span x-text="isLoading ? 'Checking visitor in...' : 'Complete Checkin'"></span>
</button>


        <!-- Notify Host Button -->
        {{-- <button @click="handleNotifyHost()"
                class="flex-1 px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50 transition-colors">
            Notify Host
        </button> --}}
    </div>

    <!-- New Scan Button -->
    {{-- <div class="text-center pt-4">
        <button @click="handleNewScan()"
                class="text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-qrcode mr-2"></i> Scan New Visitor
        </button>
    </div> --}}
</div>

<style>
    .complete-section {
        margin-bottom: 1.5rem;
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                    0 8px 10px -6px rgba(0, 0, 0, 0.04);
        width: 100%;
        max-width: 28rem;
        transition: all 0.3s ease;
    }

    .complete-section:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -6px rgba(0, 0, 0, 0.04);
    }
</style>


{{-- <div class="space-y-6">
    <!-- Visitor Information (reusing existing partial) -->
    @include('gate.partials.visitor-info')

    <!-- Vehicle Information - Fixed with null checks -->
    <div x-show="vehicleData" class="w-full max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Vehicle Information</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Mode:</span>
                <span class="capitalize" x-text="vehicleData?.modeOfArrival || 'foot'"></span>
            </div>
            <template x-if="vehicleData?.modeOfArrival === 'vehicle'">
                <div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Plate Number:</span>
                        <span class="font-mono" x-text="vehicleData?.plateNumber || ''"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="capitalize" x-text="vehicleData?.vehicleType || 'drop-off'"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Gate Operative Selection -->
    <div class="w-full max-w-md mx-auto">
        <label class="text-sm font-medium block mb-2">Verified By</label>
        <select x-model="verifiedBy"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select gate operative</option>
            <template x-for="operative in operatives" :key="operative.id">
                <option :value="operative.id" x-text="operative.name"></option>
            </template>
        </select>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 max-w-md mx-auto">
        <!-- Check-In Button -->
        <button @click="handleCheckInVisitor()"
                :disabled="!verifiedBy || isLoading"
                :class="{
                    'bg-gray-400 cursor-not-allowed': !verifiedBy || isLoading,
                    'bg-green-600 hover:bg-green-700': verifiedBy && !isLoading
                }"
                class="flex-1 px-4 py-3 text-white rounded-md font-medium transition-colors">
            <span x-text="isLoading ? 'Checking In...' : 'Check-In Visitor'"></span>
        </button>

        <!-- Notify Host Button -->
        <button @click="handleNotifyHost()"
                class="flex-1 px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50 transition-colors">
            Notify Host
        </button>
    </div>

    <!-- New Scan Button -->
    <div class="text-center pt-4">
        <button @click="handleNewScan()"
                class="text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-qrcode mr-2"></i> Scan New Visitor
        </button>
    </div>
</div> --}}
