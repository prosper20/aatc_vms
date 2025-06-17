<!-- Visitor Information Component -->
<div class="w-full max-w-md mx-auto bg-white">
    <!-- Header -->
    <div class="p-6 border-b">
        <div class="flex items-center gap-2 mb-2">
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
            {{-- <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Company:</span>
                <span class="text-right" x-text="visitorData ? visitorData.company : ''"></span>
            </div> --}}
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

<!-- Action Buttons -->
<div class="text-center mt-6 pb-6" x-show="visitorData">
    <template x-if="visitorData && visitorData.verificationPassed">
        <div class="flex w-full justify-center items-center">
            <button @click="currentStep = 'vehicle'"
                class="login-btn max-w-md">
            Continue to Mode of Arrival
        </button>
        </div>
        {{-- class="w-full max-w-md px-6 py-3 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors" --}}
    </template>
    <template x-if="visitorData && !visitorData.verificationPassed">
        <div class="space-y-4">
            <p class="text-red-600 font-medium">Cannot complete verification</p>
            <button @click="handleNewScan()"
                    class="w-full max-w-md px-6 py-3 bg-gray-200 text-gray-700 rounded-md font-medium hover:bg-gray-300 transition-colors">
                Scan Another Code
            </button>
        </div>
    </template>
</div>
