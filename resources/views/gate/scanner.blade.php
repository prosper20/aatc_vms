{{-- @extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4" x-data="scannerApp()" x-cloak>
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Scan Visitor QR Code</h1>
            <p class="text-gray-600">Visitor Management System</p>
        </div>

        <!-- Step Indicator -->
        <div class="flex justify-center mb-8">
            <div class="flex items-center gap-2 text-sm">
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                     :class="currentStep === 'scan' ? 'bg-blue-500 text-white' :
                             ['verify', 'vehicle', 'complete'].includes(currentStep) ? 'bg-green-500 text-white' : 'bg-gray-300'">
                    1
                </div>
                <div class="w-8 h-0.5 bg-gray-300"></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                     :class="currentStep === 'verify' ? 'bg-blue-500 text-white' :
                             ['vehicle', 'complete'].includes(currentStep) ? 'bg-green-500 text-white' : 'bg-gray-300'">
                    2
                </div>
                <div class="w-8 h-0.5 bg-gray-300"></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                     :class="currentStep === 'vehicle' ? 'bg-blue-500 text-white' :
                             currentStep === 'complete' ? 'bg-green-500 text-white' : 'bg-gray-300'">
                    3
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-6">
            <!-- QR Scanner Step -->
            <div x-show="currentStep === 'scan'">
                @include('gate.partials.qr-scanner')
            </div>

            <!-- Verification Step -->
            <div x-show="currentStep === 'verify'" style="display: none;">
                @include('gate.partials.visitor-info')
            </div>

            <!-- Vehicle Registration Step -->
            <div x-show="currentStep === 'vehicle'" style="display: none;">
                @include('gate.partials.vehicle-registration')
            </div>

            <!-- Complete Step -->
            <div x-show="currentStep === 'complete'" style="display: none;">
                @include('gate.partials.complete-checkin')
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-sm text-gray-500">
            © 2025 AATC Visitor Management System
        </div>
    </div>

    <!-- Toast Notifications -->
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border"
         style="display: none;">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg x-show="toastType === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toastType === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-gray-900" x-text="toastTitle"></p>
                    <p class="mt-1 text-sm text-gray-500" x-text="toastMessage"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="showToast = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function scannerApp() {
    return {
        currentStep: 'scan',
        isLoading: false,
        visitorData: null,
        vehicleData: null,
        verifiedBy: '',
        scanMode: 'camera',
        manualCode: '',
        modeOfArrival: 'foot',
        plateNumber: '',
        vehicleType: 'drop-off',
        showToast: false,
        toastType: 'success',
        toastTitle: '',
        toastMessage: '',
        operatives: @json($operatives),

        async handleCodeVerified(code) {
            console.log('Code scanned:', code);
            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ qr_data: code })
                });

                const result = await response.json();

                if (result.status === 'FOUND') {
                    this.visitorData = {
                        ...result.data,
                        verificationPassed: result.verification_passed,
                        verificationMessage: result.verification_message
                    };
                    this.currentStep = 'verify';

                    this.showToastMessage(
                        result.verification_passed ? 'success' : 'error',
                        result.verification_passed ? 'Verification Successful' : 'Verification Failed',
                        result.verification_message
                    );
                } else {
                    this.showToastMessage('error', 'Not Found', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to verify code');
                console.error('Verification error:', error);
            }

            this.isLoading = false;
        },

        async handleVehicleSaved() {
            this.isLoading = true;
            this.vehicleData = {
                modeOfArrival: this.modeOfArrival,
                plateNumber: this.modeOfArrival === 'vehicle' ? this.plateNumber.toUpperCase() : '',
                vehicleType: this.modeOfArrival === 'vehicle' ? this.vehicleType : 'drop-off'
            };

            // Simulate delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            this.currentStep = 'complete';
            this.isLoading = false;

            this.showToastMessage('success', 'Vehicle Saved', 'Vehicle information saved successfully');
        },

        async handleCheckInVisitor() {
            if (!this.verifiedBy) {
                this.showToastMessage('error', 'Missing Information', 'Please select the gate operative');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        visit_id: this.visitorData.visit_id,
                        mode_of_arrival: this.vehicleData.modeOfArrival,
                        plate_number: this.vehicleData.plateNumber,
                        vehicle_type: this.vehicleData.vehicleType,
                        verified_by: this.verifiedBy
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Visitor Checked In', result.message);

                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.handleNewScan();
                    }, 2000);
                } else {
                    this.showToastMessage('error', 'Check-in Failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to check in visitor');
                console.error('Check-in error:', error);
            }

            this.isLoading = false;
        },

        async handleNotifyHost() {
            try {
                const response = await fetch('{{ route("gate.scanner.notify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        host_id: this.visitorData.host_id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Host Notified', result.message);
                } else {
                    this.showToastMessage('error', 'Notification Failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to notify host');
                console.error('Notification error:', error);
            }
        },

        handleNewScan() {
            this.currentStep = 'scan';
            this.visitorData = null;
            this.vehicleData = null;
            this.verifiedBy = '';
            this.manualCode = '';
            this.plateNumber = '';
            this.modeOfArrival = 'foot';
            this.vehicleType = 'drop-off';
        },

        handleManualSubmit() {
            if (this.manualCode.trim()) {
                this.handleCodeVerified(this.manualCode.trim().toUpperCase());
                this.manualCode = '';
            }
        },

        handleCameraScan() {
            // Simulate QR code scanning
            const simulatedCode = '307D79C5++';
            this.handleCodeVerified(simulatedCode);
        },

        showToastMessage(type, title, message) {
            this.toastType = type;
            this.toastTitle = title;
            this.toastMessage = message;
            this.showToast = true;

            // Auto hide after 5 seconds
            setTimeout(() => {
                this.showToast = false;
            }, 5000);
        }
    }
}
</script>
@endsection --}}

@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #07AF8B;
        --primary-dark: #007570;
        --accent: #FFCA00;
        --light-bg: #f8fafc;
        --text: #334155;
        --text-light: #64748b;
        /* --border: #e4e6e8; */
        --border: #cbd5e1
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--light-bg);
        color: var(--text);
        line-height: 1.5;
        background-image:
            radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.1) 0, transparent 50%),
            radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.1) 0, transparent 50%);
    }

    .scanner-container {
        min-height: 100vh;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .scanner-wrapper {
        width: 100%;
        max-width: 42rem;
        position: relative;
    }

    .scanner-card {
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                    0 8px 10px -6px rgba(0, 0, 0, 0.04);
        padding: 2.5rem;
        width: 100%;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .scanner-card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -6px rgba(0, 0, 0, 0.04);
    }

    .header-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .header-section h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .header-section p {
        color: var(--text-light);
        font-size: 0.875rem;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .step-indicator .steps {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .step-circle {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        transition: all 0.2s;
    }

    .step-circle.active {
        background-color: var(--primary);
        color: white;
    }

    .step-circle.completed {
        background-color: var(--accent);
        color: var(--text);
    }

    .step-circle.inactive {
        background-color: var(--border);
        color: var(--text-light);
    }

    .step-line {
        width: 2rem;
        height: 0.125rem;
        background-color: var(--border);
    }

    .content-section {
        margin-bottom: 2rem;

        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                    0 8px 10px -6px rgba(0, 0, 0, 0.04);
        width: 100%;
        transition: all 0.3s ease;
    }

    .content-section:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -6px rgba(0, 0, 0, 0.04);
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .input-field {
        position: relative;
    }

    .input-field input,
    .input-field select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .input-field input:focus,
    .input-field select:focus {
        outline: none;
        border-color: #d3f2ee;
        /* box-shadow: 0 0 0 3px rgba(7, 175, 139, 0.15); */
        background-color: white;
    }

    .btn {
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background-color: var(--accent);
        color: var(--text);
    }

    .btn-secondary:hover {
        background-color: #e6b800;
        transform: translateY(-1px);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover {
        background-color: var(--primary);
        color: white;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .info-card {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-card.error {
        background-color: #fef2f2;
        border-color: #fecaca;
    }

    .info-card.warning {
        background-color: #fffbeb;
        border-color: #fed7aa;
    }

    .visitor-details {
        display: grid;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 500;
        color: var(--text-light);
    }

    .detail-value {
        color: var(--text);
        font-weight: 500;
    }

    .footer-section {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;

        font-size: 0.8125rem;
        color: var(--text-light);
    }

    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 50;
        max-width: 24rem;
        width: 100%;
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
    }

    .toast-content {
        padding: 1rem;
    }

    .toast-header {
        display: flex;
        align-items: flex-start;
    }

    .toast-icon {
        flex-shrink: 0;
        margin-right: 0.75rem;
    }

    .toast-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .toast-icon.success svg {
        color: var(--primary);
    }

    .toast-icon.error svg {
        color: #dc2626;
    }

    .toast-body {
        flex: 1;
        padding-top: 0.125rem;
    }

    .toast-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text);
    }

    .toast-message {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--text-light);
    }

    .toast-close {
        margin-left: 1rem;
        flex-shrink: 0;
        background: transparent;
        border: none;
        cursor: pointer;
        color: var(--text-light);
        padding: 0;
    }

    .toast-close:hover {
        color: var(--text);
    }

    .mode-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .mode-option {
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }

    .mode-option.active {
        border-color: var(--primary);
        background-color: rgba(7, 175, 139, 0.05);
    }

    .mode-option:hover {
        border-color: var(--primary);
    }

    @media (max-width: 640px) {
        .scanner-card {
            padding: 1.75rem;
        }

        .mode-selector {
            grid-template-columns: 1fr;
        }
    }
    .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-light);
            font-size: 0.875rem;
        }
        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo img {
            height: 3.5rem;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        @media (max-width: 768px) {
            .language-switcher {
                top: 5px;
                right: 10px;
            }
        }
</style>
<div class="language-switcher">
    @include('partials.language_switcher')
</div>
<div class="scanner-container" x-data="scannerApp()" x-cloak>
    <div class="scanner-wrapper">
        <div class="">
            <div class="logo">
                <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="AATC-VMS Logo">
            </div>

            <div class="login-header">
                <h1>{{ __("Security Verification Portal") }}</h1>
                <p>{{ __("Scan Visitor QR Code") }}</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="steps">
                    <div class="step-circle"
                         :class="currentStep === 'scan' ? 'active' :
                                 ['verify', 'vehicle', 'complete'].includes(currentStep) ? 'completed' : 'inactive'">
                        1
                    </div>
                    <div class="step-line"></div>
                    <div class="step-circle"
                         :class="currentStep === 'verify' ? 'active' :
                                 ['vehicle', 'complete'].includes(currentStep) ? 'completed' : 'inactive'">
                        2
                    </div>
                    <div class="step-line"></div>
                    <div class="step-circle"
                         :class="currentStep === 'vehicle' ? 'active' :
                                 currentStep === 'complete' ? 'completed' : 'inactive'">
                        3
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div
             x-show="['scan', 'verify', 'vehicle'].includes(currentStep)"
             class="content-section">
                <!-- QR Scanner Step -->
                <div x-show="currentStep === 'scan'">
                    @include('gate.partials.qr-scanner')
                </div>

                <!-- Verification Step -->
                <div x-show="currentStep === 'verify'" style="display: none;">
                    @include('gate.partials.visitor-info')
                </div>

                <!-- Vehicle Registration Step -->
                <div x-show="currentStep === 'vehicle'" style="display: none;">
                    @include('gate.partials.vehicle-registration')
                </div>

                <!-- Complete Step -->
                {{-- <div x-show="currentStep === 'complete'" style="display: none;">
                    @include('gate.partials.complete-checkin')
                </div> --}}
            </div>

            <div x-show="currentStep === 'complete'" style="width: 100%;" class="w-full flex items-center justify-center">
                <div x-show="currentStep === 'complete'" style="display: none;" class="w-full">
                    @include('gate.partials.complete-checkin')
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-section">
                © 2025 AATC Visitor Management System
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="toast"
         style="display: none;">
        <div class="toast-content">
            <div class="toast-header">
                <div class="toast-icon" :class="toastType">
                    <svg x-show="toastType === 'success'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toastType === 'error'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title" x-text="toastTitle"></p>
                    <p class="toast-message" x-text="toastMessage"></p>
                </div>
                <button @click="showToast = false" class="toast-close">
                    <span class="sr-only">Close</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include HTML5 QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
function scannerApp() {
    return {
        currentStep: 'scan',
        isLoading: false,
        visitorData: null,
        vehicleData: null,
        verifiedBy: '',
        scanMode: 'camera',
        manualCode: '',
        modeOfArrival: 'foot',
        plateNumber: '',
        vehicleType: 'drop-off',
        showToast: false,
        toastType: 'success',
        toastTitle: '',
        toastMessage: '',
        operatives: @json($operatives),
        html5QrcodeScanner: null,
        scannerStarted: false,

        init() {
            // Initialize scanner when component is ready
            this.$nextTick(() => {
                this.initializeScanner();
            });
        },

        initializeScanner() {
            if (this.html5QrcodeScanner) {
                return;
            }

            this.html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10,
                qrbox: 250,
                rememberLastUsedCamera: true
            });

            this.html5QrcodeScanner.render(
                (decodedText, decodedResult) => {
                    this.onScanSuccess(decodedText, decodedResult);
                },
                (error) => {
                    // Handle scan errors silently
                    console.log('Scan error:', error);
                }
            );

            this.scannerStarted = true;
        },

        onScanSuccess(decodedText, decodedResult) {
            // Stop scanner after successful scan
            if (this.html5QrcodeScanner) {
                this.html5QrcodeScanner.clear().then(() => {
                    console.log("QR Scanner stopped");
                    this.scannerStarted = false;
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            }

            // Process the scanned code
            this.handleCodeVerified(decodedText);
        },

        restartScanner() {
            if (this.scannerStarted) {
                return;
            }

            this.$nextTick(() => {
                this.initializeScanner();
            });
        },

        async handleCodeVerified(code) {
            console.log('Code scanned:', code);
            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ qr_data: code })
                });

                const result = await response.json();

                if (result.status === 'FOUND') {
                    this.visitorData = {
                        ...result.data,
                        verificationPassed: result.verification_passed,
                        verificationMessage: result.verification_message
                    };
                    this.currentStep = 'verify';

                    this.showToastMessage(
                        result.verification_passed ? 'success' : 'error',
                        result.verification_passed ? 'Verification Successful' : 'Verification Failed',
                        result.verification_message
                    );
                } else {
                    this.showToastMessage('error', 'Not Found', result.message);
                    // Restart scanner for new attempt
                    setTimeout(() => {
                        this.restartScanner();
                    }, 2000);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to verify code');
                console.error('Verification error:', error);
                // Restart scanner for new attempt
                setTimeout(() => {
                    this.restartScanner();
                }, 2000);
            }

            this.isLoading = false;
        },

        async handleVehicleSaved() {
            this.isLoading = true;
            this.vehicleData = {
                modeOfArrival: this.modeOfArrival,
                plateNumber: this.modeOfArrival === 'vehicle' ? this.plateNumber.toUpperCase() : '',
                vehicleType: this.modeOfArrival === 'vehicle' ? this.vehicleType : 'drop-off'
            };

            // Simulate delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            this.currentStep = 'complete';
            this.isLoading = false;

            this.showToastMessage('success', 'Saved', 'Mode of Arrival saved successfully');
        },

        async handleCheckInVisitor() {
            if (!this.verifiedBy) {
                this.showToastMessage('error', 'Missing Information', 'Please select the operative');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        visit_id: this.visitorData.visit_id,
                        mode_of_arrival: this.vehicleData.modeOfArrival,
                        plate_number: this.vehicleData.plateNumber,
                        vehicle_type: this.vehicleData.vehicleType,
                        verified_by: this.verifiedBy
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Visitor Checked In', result.message);

                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.handleNewScan();
                    }, 2000);
                } else {
                    this.showToastMessage('error', 'Security verification failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Security verification failed');
                console.error('Check-in error:', error);
            }

            this.isLoading = false;
        },

        async handleNotifyHost() {
            try {
                const response = await fetch('{{ route("gate.scanner.notify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        host_id: this.visitorData.host_id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Host Notified', result.message);
                } else {
                    this.showToastMessage('error', 'Notification Failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to notify host');
                console.error('Notification error:', error);
            }
        },

        handleNewScan() {
            // Clear scanner if exists
            if (this.html5QrcodeScanner && this.scannerStarted) {
                this.html5QrcodeScanner.clear().then(() => {
                    this.scannerStarted = false;
                    this.html5QrcodeScanner = null;
                }).catch(err => {
                    console.error("Failed to clear scanner", err);
                });
            }

            // Reset all data
            this.currentStep = 'scan';
            this.visitorData = null;
            this.vehicleData = null;
            this.verifiedBy = '';
            this.manualCode = '';
            this.plateNumber = '';
            this.modeOfArrival = 'foot';
            this.vehicleType = 'drop-off';
            this.scanMode = 'camera';

            // Restart scanner
            this.$nextTick(() => {
                this.initializeScanner();
            });
        },

        handleManualSubmit() {
            if (this.manualCode.trim()) {
                this.handleCodeVerified(this.manualCode.trim().toUpperCase());
                this.manualCode = '';
            }
        },

        showToastMessage(type, title, message) {
            this.toastType = type;
            this.toastTitle = title;
            this.toastMessage = message;
            this.showToast = true;

            // Auto hide after 5 seconds
            setTimeout(() => {
                this.showToast = false;
            }, 5000);
        }
    }
}
</script>
@endsection
{{-- <div class="scanner-container" x-data="scannerApp()" x-cloak>
    <div class="scanner-wrapper">
        <div class="">
            <div class="logo">
                <img src="{{ asset('assets/logo-green-yellow.png') }}" alt="AATC-VMS Logo">
            </div>

            <div class="login-header">
                <h1>{{ __("Security Verification Portal") }}</h1>
                <p>{{ __("Scan Visitor QR Code") }}</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="steps">
                    <div class="step-circle"
                         :class="currentStep === 'scan' ? 'active' :
                                 ['verify', 'vehicle', 'complete'].includes(currentStep) ? 'completed' : 'inactive'">
                        1
                    </div>
                    <div class="step-line"></div>
                    <div class="step-circle"
                         :class="currentStep === 'verify' ? 'active' :
                                 ['vehicle', 'complete'].includes(currentStep) ? 'completed' : 'inactive'">
                        2
                    </div>
                    <div class="step-line"></div>
                    <div class="step-circle"
                         :class="currentStep === 'vehicle' ? 'active' :
                                 currentStep === 'complete' ? 'completed' : 'inactive'">
                        3
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content-section">
                <!-- QR Scanner Step -->
                <div x-show="currentStep === 'scan'">
                    @include('gate.partials.qr-scanner')
                </div>

                <!-- Verification Step -->
                <div x-show="currentStep === 'verify'" style="display: none;">
                    @include('gate.partials.visitor-info')
                </div>

                <!-- Vehicle Registration Step -->
                <div x-show="currentStep === 'vehicle'" style="display: none;">
                    @include('gate.partials.vehicle-registration')
                </div>

                <!-- Complete Step -->
                <div x-show="currentStep === 'complete'" style="display: none;">
                    @include('gate.partials.complete-checkin')
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-section">
                © 2025 AATC Visitor Management System
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="toast"
         style="display: none;">
        <div class="toast-content">
            <div class="toast-header">
                <div class="toast-icon" :class="toastType">
                    <svg x-show="toastType === 'success'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="toastType === 'error'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title" x-text="toastTitle"></p>
                    <p class="toast-message" x-text="toastMessage"></p>
                </div>
                <button @click="showToast = false" class="toast-close">
                    <span class="sr-only">Close</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function scannerApp() {
    return {
        currentStep: 'scan',
        isLoading: false,
        visitorData: null,
        vehicleData: null,
        verifiedBy: '',
        scanMode: 'camera',
        manualCode: '',
        modeOfArrival: 'foot',
        plateNumber: '',
        vehicleType: 'drop-off',
        showToast: false,
        toastType: 'success',
        toastTitle: '',
        toastMessage: '',
        operatives: @json($operatives),

        async handleCodeVerified(code) {
            console.log('Code scanned:', code);
            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ qr_data: code })
                });

                const result = await response.json();

                if (result.status === 'FOUND') {
                    this.visitorData = {
                        ...result.data,
                        verificationPassed: result.verification_passed,
                        verificationMessage: result.verification_message
                    };
                    this.currentStep = 'verify';

                    this.showToastMessage(
                        result.verification_passed ? 'success' : 'error',
                        result.verification_passed ? 'Verification Successful' : 'Verification Failed',
                        result.verification_message
                    );
                } else {
                    this.showToastMessage('error', 'Not Found', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to verify code');
                console.error('Verification error:', error);
            }

            this.isLoading = false;
        },

        async handleVehicleSaved() {
            this.isLoading = true;
            this.vehicleData = {
                modeOfArrival: this.modeOfArrival,
                plateNumber: this.modeOfArrival === 'vehicle' ? this.plateNumber.toUpperCase() : '',
                vehicleType: this.modeOfArrival === 'vehicle' ? this.vehicleType : 'drop-off'
            };

            // Simulate delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            this.currentStep = 'complete';
            this.isLoading = false;

            this.showToastMessage('success', 'Vehicle Saved', 'Vehicle information saved successfully');
        },

        async handleCheckInVisitor() {
            if (!this.verifiedBy) {
                this.showToastMessage('error', 'Missing Information', 'Please select the gate operative');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('{{ route("gate.scanner.checkin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        visit_id: this.visitorData.visit_id,
                        mode_of_arrival: this.vehicleData.modeOfArrival,
                        plate_number: this.vehicleData.plateNumber,
                        vehicle_type: this.vehicleData.vehicleType,
                        verified_by: this.verifiedBy
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Visitor Checked In', result.message);

                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.handleNewScan();
                    }, 2000);
                } else {
                    this.showToastMessage('error', 'Check-in Failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to check in visitor');
                console.error('Check-in error:', error);
            }

            this.isLoading = false;
        },

        async handleNotifyHost() {
            try {
                const response = await fetch('{{ route("gate.scanner.notify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        host_id: this.visitorData.host_id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    this.showToastMessage('success', 'Host Notified', result.message);
                } else {
                    this.showToastMessage('error', 'Notification Failed', result.message);
                }
            } catch (error) {
                this.showToastMessage('error', 'Error', 'Failed to notify host');
                console.error('Notification error:', error);
            }
        },

        handleNewScan() {
            this.currentStep = 'scan';
            this.visitorData = null;
            this.vehicleData = null;
            this.verifiedBy = '';
            this.manualCode = '';
            this.plateNumber = '';
            this.modeOfArrival = 'foot';
            this.vehicleType = 'drop-off';
        },

        handleManualSubmit() {
            if (this.manualCode.trim()) {
                this.handleCodeVerified(this.manualCode.trim().toUpperCase());
                this.manualCode = '';
            }
        },

        handleCameraScan() {
            // Simulate QR code scanning
            const simulatedCode = '307D79C5++';
            this.handleCodeVerified(simulatedCode);
        },

        showToastMessage(type, title, message) {
            this.toastType = type;
            this.toastTitle = title;
            this.toastMessage = message;
            this.showToast = true;

            // Auto hide after 5 seconds
            setTimeout(() => {
                this.showToast = false;
            }, 5000);
        }
    }
}
</script>
@endsection
 --}}
