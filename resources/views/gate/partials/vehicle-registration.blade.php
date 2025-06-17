<!-- Vehicle Registration Component -->
<div class="w-full max-w-md mx-auto bg-white">
    <!-- Header -->
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Mode of Arrival</h3>
    </div>

    <!-- Content -->
    <div class="p-6 space-y-6">
        <!-- Mode of Arrival -->
        <div>
            {{-- <label class="text-base font-semibold mb-3 block">Mode of Arrival</label> --}}
            <div class="flex gap-6">
                <!-- On Foot -->
                <div class="custom-radio">
                    <input type="radio"
                           x-model="modeOfArrival"
                           value="foot"
                           id="foot">
                    <label for="foot">
                        <i class="fas fa-walking text-gray-600"></i>
                        On Foot
                    </label>
                </div>

                <!-- Vehicle -->
                <div class="custom-radio">
                    <input type="radio"
                           x-model="modeOfArrival"
                           value="vehicle"
                           id="vehicle">
                    <label for="vehicle">
                        <i class="fas fa-car text-gray-600"></i>
                        Vehicle
                    </label>
                </div>
            </div>
            {{-- <div class="flex gap-6">
                <div class="flex items-center space-x-2">
                    <input type="radio"
                           x-model="modeOfArrival"
                           value="foot"
                           id="foot"
                           class="text-blue-600 focus:ring-blue-500">
                    <label for="foot" class="flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-walking text-gray-600"></i>
                        On Foot
                    </label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="radio"
                           x-model="modeOfArrival"
                           value="vehicle"
                           id="vehicle"
                           class="text-blue-600 focus:ring-blue-500">
                    <label for="vehicle" class="flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-car text-gray-600"></i>
                        Vehicle
                    </label>
                </div>
            </div> --}}
        </div>

        <!-- Vehicle Details -->
        {{-- <div x-show="modeOfArrival === 'vehicle'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="space-y-4 p-4 bg-blue-50 rounded-lg"
             style="display --}}

             <div x-show="modeOfArrival === 'vehicle'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="space-y-4 p-4 bg-blue-50 rounded-lg"
             style="display: none;">
            <!-- Plate Number -->
            {{-- <div>
                <label for="plateNumber" class="text-sm font-medium block mb-1">
                    Vehicle Plate Number *
                </label>
                <input id="plateNumber"
                       type="text"
                       x-model="plateNumber"
                       placeholder="e.g., 12264648"
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 font-mono text-center"
                       style="text-transform: uppercase;">
            </div>

            <!-- Vehicle Type -->
            <div>
                <label class="text-sm font-medium block mb-1">Vehicle Mode *</label>
                <select x-model="vehicleType"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="drop-off">Drop-off (Leaving)</option>
                    <option value="wait">Waiting (Parking)</option>
                </select>
            </div> --}}
            <!-- Plate Number -->
<div>
    <label for="plateNumber" class="text-sm font-medium block mb-1">
        Vehicle Plate Number *
    </label>
    <input
        id="plateNumber"
        type="text"
        x-model="plateNumber"
        placeholder="e.g., GWA-123-NV"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-[0.9375rem] bg-[#f8fafc] transition-all duration-200 focus:outline-none focus:border-[#d3f2ee] focus:bg-white"
        style="text-transform: uppercase;"
    >
</div>

<!-- Vehicle Type -->
<div>
    <label class="text-sm font-medium block mb-1">Vehicle Mode *</label>
    <select
        x-model="vehicleType"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-[0.9375rem] bg-[#f8fafc] transition-all duration-200 focus:outline-none focus:border-[#d3f2ee] focus:bg-white"
    >
        <option value="drop-off">Drop-off (Leaving)</option>
        <option value="wait">Waiting (Parking)</option>
    </select>
</div>

        </div>

        <!-- Save Button -->
        {{-- <button @click="handleVehicleSaved()"
                :disabled="(modeOfArrival === 'vehicle' && !plateNumber.trim()) || isLoading"
                :class="{
                    'bg-gray-400 cursor-not-allowed': (modeOfArrival === 'vehicle' && !plateNumber.trim()) || isLoading,
                    'bg-blue-600 hover:bg-blue-700': !((modeOfArrival === 'vehicle' && !plateNumber.trim()) || isLoading)
                }"
                class="login-btn">
            <span x-text="isLoading ? 'Saving...' : 'Save'"></span>
        </button> --}}
        <button
    @click="handleVehicleSaved()"
    :disabled="(modeOfArrival === 'vehicle' && !plateNumber.trim()) || isLoading"
    :class="[
        'w-full px-4 py-4 rounded-xl text-white text-base font-medium flex items-center justify-center gap-2 transition-all duration-200',
        (modeOfArrival === 'vehicle' && !plateNumber.trim()) || isLoading
            ? 'bg-gray-400 cursor-not-allowed'
            : 'bg-[#07AF8B] hover:bg-[#007570] active:translate-y-0 hover:-translate-y-px cursor-pointer'
    ]"
>
    <span x-text="isLoading ? 'Saving...' : 'Save'"></span>
</button>

    </div>
</div>

<style>
    .custom-radio input[type="radio"] {
        display: none;
    }

    .custom-radio input[type="radio"] + label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .custom-radio input[type="radio"] + label::before {
        content: "";
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 1px solid #4b5563;
        border-radius: 9999px;
        background-color: white;
        transition: all 0.2s ease;
    }

    .custom-radio input[type="radio"]:checked + label::before {
        background-color: #e6b800;
        box-shadow: inset 0 0 0 4px white;
        border: 1px solid #e6b800;
    }
</style>


{{-- class="w-full px-4 py-3 text-white rounded-md font-medium transition-colors"> --}}
