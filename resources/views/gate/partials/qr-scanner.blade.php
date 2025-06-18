<!-- QR Scanner Component -->
<div class="w-full max-w-md mx-auto bg-white ">
    <!-- Content -->
    <div class="px-6 py-8 space-y-4">
        <!-- Mode Toggle -->
        <div class="flex gap-2 mb-4">
            <button @click="scanMode = 'camera'"
                    :class="scanMode === 'camera' ? 'bg-yellow-100 text-[#007570]' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-camera w-4 h-4"></i>
                Camera
            </button>
            <button @click="scanMode = 'manual'"
                    :class="scanMode === 'manual' ? 'bg-yellow-100 text-[#007570]' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-keyboard w-4 h-4"></i>
                Manual
            </button>
        </div>

        <!-- Camera Mode -->
        <div x-show="scanMode === 'camera'" class="text-center space-y-4">
            {{-- <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 bg-gray-50">
                <div class="text-4xl mb-2">📷</div>
                <p class="text-sm text-gray-600 mb-4">Position QR code within frame</p>
                <button @click="handleCameraScan()"
                        :disabled="isLoading"
                        :class="isLoading ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                        class="w-full px-4 py-2 text-white rounded-md font-medium transition-colors">
                    <span x-text="isLoading ? 'Scanning...' : 'Request Camera Permissions'"></span>
                </button>
            </div>
            <button class="login-btn">
                Waiting for scan...
            </button> --}}
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                <div id="reader" style="width: 100%;"></div>
                <div x-show="!scannerStarted && !isLoading" class="text-center p-4">
                    <div class="text-4xl mb-2">📷</div>
                    <p class="text-sm text-gray-600 mb-4">Click to start camera scanner</p>
                    <button @click="initializeScanner()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors">
                        Start Camera Scanner
                    </button>
                </div>
                <div x-show="isLoading" class="text-center p-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                    <p class="text-sm text-gray-600">Processing...</p>
                </div>
            </div>

            {{-- <div x-show="scannerStarted && !isLoading" class="login-btn bg-green-100 text-green-800 border-green-300">
                Scanner active - Position QR code in frame
            </div>
            <div x-show="isLoading" class="login-btn bg-blue-100 text-blue-800 border-blue-300">
                Verifying code...
            </div> --}}
            <button  x-show="scannerStarted && !isLoading" class="login-btn">
                Waiting for scan...
            </button>
            <button  x-show="isLoading" class="login-btn">
                Verifying code...
            </button>
        </div>

        <!-- Manual Mode -->
        <form method="POST" x-show="scanMode === 'manual'" style="display: none;" action="{{ route('sm.login.submit') }}">
            @csrf
            <div class="form-group">
                {{-- <label for="email">{{ __("Email Address") }}</label> --}}
                <div class="input-field">
                    {{-- <i class="fas fa-envelope icon"></i> --}}
                    <input type="text"
                    x-model="manualCode"
                    @keyup.enter="handleManualSubmit()" id="code" name="code" required
                           placeholder="{{ __('Enter Unique Code Manually') }}" autocomplete="email">
                </div>
            </div>

            {{-- <button type="submit" @click="handleManualSubmit()"
                    :disabled="!manualCode.trim() || isLoading"
                    :class="(!manualCode.trim() || isLoading) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                    class="login-btn">
                <span x-text="isLoading ? 'Verifying...' : 'Search'"></span>
            </button> --}}

            <button
    type="submit"
    @click="handleManualSubmit()"
    :disabled="!manualCode.trim() || isLoading"
    :class="[
        'w-full px-4 py-4 rounded-xl text-white text-base font-medium flex items-center justify-center gap-2 transition-all duration-200',
        (!manualCode.trim() || isLoading)
            ? 'bg-gray-400 cursor-not-allowed'
            : 'bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer'
    ]"
>
    <span x-text="isLoading ? 'Verifying...' : 'Search'"></span>
</button>


            {{-- <button type="submit" @click="handleManualSubmit()"
            :disabled="!manualCode.trim() || isLoading"
            :class="(!manualCode.trim() || isLoading) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
            class="login-btn">
        <span x-text="isLoading ? {{ __("Verifying...") }} : {{ __("Search") }}"></span>

            </button> --}}
        </form>
        {{-- <div x-show="scanMode === 'manual'" class="space-y-4" style="display: none;">
            <input type="text"
                   x-model="manualCode"
                   @keyup.enter="handleManualSubmit()"
                   placeholder="Enter Unique Code Manually"
                   class="w-full text-center text-lg font-mono px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                   style="text-transform: uppercase;">
            <button @click="handleManualSubmit()"
                    :disabled="!manualCode.trim() || isLoading"
                    :class="(!manualCode.trim() || isLoading) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                    class="login-btn">
                <span x-text="isLoading ? 'Verifying...' : 'Search'"></span>
            </button>
        </div> --}}
    </div>
</div>

<style>
    /* QR Scanner specific styles */
    #reader {
        border: none !important;
    }

    #reader > div {
        border: none !important;
    }

    #reader video {
        border-radius: 8px;
        max-width: 100%;
        height: auto;
    }

    #reader__scan_region {
        border-radius: 8px;
    }

    #reader__dashboard {
        background-color: transparent !important;
    }

    #reader__dashboard_section {
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-top: 10px;
        padding: 8px;
    }

    #reader__dashboard_section_csr button {
        background-color: #007570 !important;
        border: none !important;
        border-radius: 4px !important;
        color: white !important;
        padding: 8px 16px !important;
        font-size: 14px !important;
    }

    #reader__dashboard_section_csr button:hover {
        background-color: #005a56 !important;
    }

    </style>
