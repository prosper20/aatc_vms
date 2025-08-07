<div class="mx-auto bg-white w-80 h-[500px] shadow-xl overflow-hidden relative pass-body">
    <!-- Corner Decorations -->
    <!-- <div class="absolute top-0 right-0 w-[30px] h-[30px] border-t-2 border-r-2 border-[#0d9488]"></div>
    <div class="absolute top-0 left-0 w-[30px] h-[30px] border-t-2 border-l-2 border-[#0d9488]"></div> -->
    <div class="absolute bottom-0 right-0 w-[30px] h-[30px] border-b-2 border-r-2 border-[#0d9488]"></div>
    <div class="absolute bottom-0 left-0 w-[30px] h-[30px] border-b-2 border-l-2 border-[#0d9488]"></div>

    <!-- Green Curved Header -->
    <div class="absolute top-0 left-0 w-full h-42 bg-[#278582] rounded-b-[50%] z-0"></div>

    <!-- Top Logo (inside the green curve) -->
    <div class="absolute top-4 w-full flex justify-center z-10">
    <img src="/assets/aatc-logo-white-text.svg" alt="Top Logo" class="max-w-[120px] h-auto" />
    </div>

    <!-- Visitor Photo (pushed down to fit logo) -->
    <div class="relative z-10 flex justify-center mt-20">
        <div class="w-36 h-36 bg-white rounded-full overflow-hidden border-4 border-white shadow-md relative group cursor-pointer" onclick="showPhotoOptions()">
            <div id="visitor-photo" class="w-full h-full flex items-center justify-center bg-gray-100 text-[#0d9488]">
                {{-- <i class="fas text-[#0d9488] fa-user text-4xl" id="default-icon"></i>
                <img id="visitor-image" class="w-full h-full object-cover hidden" alt="Visitor Photo" /> --}}
                @if($visitor->hasPhoto())
                    <img src="{{ $visitor->photo_url }}" alt="Visitor Photo" class="w-full h-full object-cover" />
                @else
                    <i class="fas text-[#0d9488] fa-user text-4xl"></i>
                @endif
            </div>

            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <i class="fas fa-camera text-white text-xl"></i>
            </div>
        </div>

        <!-- Photo Options Modal -->
        <div id="photo-options-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[80] hidden flex flex-col gap-3 items-center justify-center">
            <div class="hidden md:inline-flex opacity-0 min-w-full h-10 items-center justify-center bg-transparent px-1 py-2 text-gray-500 w-full max-w-xs mx-auto"></div>
            <div class="bg-white z-[60] p-6 w-80 h-[500px] mx-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Add Guest Photo</h3>

                <div class="space-y-3">
                    <!-- Upload from device -->
                    <button onclick="triggerFileUpload()" class="w-full bg-[#0d9488] text-white py-3 px-4 rounded-lg hover:bg-[#0f766e] transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-upload"></i>
                        <span>Upload from Device</span>
                    </button>

                    <!-- Take photo -->
                    <button onclick="openCamera()" class="w-full bg-[#e6b800] hover:bg-[#ffcd00] text-white py-3 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-camera"></i>
                        <span>Take with Camera</span>
                    </button>

                    <!-- Use image URL -->
                    <button onclick="showUrlInput()" class="w-full bg-[#5bba47] text-white py-3 px-4 rounded-lg hover:bg-[#4fa03d] transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-link"></i>
                        <span>Use Image Link</span>
                    </button>

                    <!-- Remove photo (only show if photo exists) -->
                    <button id="remove-photo-btn" onclick="removePhoto()" class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center space-x-2 hidden">
                        <i class="fas fa-trash"></i>
                        <span>Remove Photo</span>
                    </button>
                </div>

                <!-- URL Input Section -->
                <div id="url-input-section" class="hidden mt-4">
                    <input type="url" id="image-url-input" placeholder="Enter image URL..." class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#d3f2ee] focus:bg-white transition-colors duration-200 mb-3">
                    <div class="flex space-x-2">
                        <button onclick="loadFromUrl()" class="flex-1 bg-[#0d9488] text-white py-2 px-4 rounded-lg hover:bg-[#0f766e] transition-colors">
                            Load Image
                        </button>
                        <button onclick="hideUrlInput()" class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>

                <!-- Camera Section -->
                <div id="camera-section" class="hidden mt-4">
                    <video id="camera-video" class="w-full rounded-lg mb-3" style="max-height: 200px;"></video>
                    <div class="flex space-x-2">
                        <button onclick="capturePhoto()" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-camera mr-2"></i>Capture
                        </button>
                        <button onclick="stopCamera()" class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                    </div>
                    <canvas id="camera-canvas" class="hidden"></canvas>
                </div>

                <button onclick="closePhotoOptions()" class="w-full mt-4 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden file input -->
    <input type="file" id="photo-upload" accept="image/*" class="hidden" onchange="handleFileUpload(event)">

    <div class="text-2xl font-bold text-gray-800 my-2 venue-font text-center">
      <div>{{ $visitor->name ?? '---' }}</div>
      <div class="text-sm text-gray-600">
        Duration: <span id="card-valid-until">{{ $duration ?? '---' }}</span>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6 relative flex flex-col">
      <div class="p-4 flex-1 flex flex-col justify-center">
        <!-- Decorative divider with centered GUEST label -->
        <div class="flex items-center justify-center mb-6 space-x-4">
            <div class="flex-1 h-[2px] bg-gradient-to-r from-transparent via-[#0d9488] to-[#0d9488] rounded-full"></div>
            <div class="text-[#0d9488] text-lg font-semibold text-font whitespace-nowrap">GUEST</div>
            <div class="flex-1 h-[2px] bg-gradient-to-r from-transparent via-[#0d9488] to-[#0d9488] rounded-full"></div>
        </div>

        <!-- Important Notice -->
        <div class="text-center ">
          <div class="text-lg font-semibold text-gray-800 leading-relaxed text-font">
            Must be visibly worn at all times while on premises
          </div>
        </div>
      </div>

    </div>
    <!-- Logo at Bottom -->
    <div class="absolute bottom-0 right-0">
        <img src="/assets/Picture-nobg.png" alt="Bottom Logo" class="h-[9rem] opacity-30" />
      </div>
      <!-- Subtle pattern overlay -->
    <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMzAgMTVMMTUgMzAgMzAgNDUgNDUgMzB6IiBzdHJva2U9IiMwZDk0ODgiIHN0cm9rZS13aWR0aD0iMS41IiBmaWxsPSJub25lIi8+PC9zdmc+')]"></div>
  </div>
