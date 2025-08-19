<div class="mx-auto bg-white w-80 h-[500px] shadow-xl flex overflow-hidden relative pass-body">
    <!-- Corner Decorations -->
    <div class="corner-decoration top-left"></div>
    <div class="corner-decoration top-right"></div>
    <div class="corner-decoration bottom-left"></div>
    <div class="corner-decoration bottom-right"></div>

    <!-- Main Content -->
    <div class="flex-1 p-4 relative flex flex-col">
      <!-- Header -->
      <div class="flex items-center justify-center mb-6">
        <img src="{{ asset('assets/Picture2.jpeg') }}" alt="Top Logo" class="w-[10rem] h-auto" />
      </div>

      <!-- Content Container -->
      <div class="p-4 flex-1 flex flex-col z-20 pt-[2.5rem] items-center">
        <!-- Decorative element -->
        <div class="w-3/4 h-1 bg-gradient-to-r from-transparent via-[#0d9488] to-transparent mb-6 rounded-full"></div>

        <div class="mb-6 text-center">
          <div class="text-base font-medium text-red-700 leading-relaxed">
            This card is a property of Afreximbank African Trade Center.
          </div>
        </div>

        <div class="mb-6 text-center">
          <div class="text-base font-medium text-gray-800">
            Return this card at the end of your visit.
          </div>
        </div>

        <div class="text-center">
          <div class="text-base font-medium text-gray-800">
            If found please return to Afreximbank African Trade Center Abuja.
          </div>
        </div>

        <!-- Decorative element -->
        <div class="w-3/4 h-1 bg-gradient-to-r from-transparent via-[#0d9488] to-transparent mt-6 rounded-full"></div>
      </div>

      <!-- Fixed Bottom-Right Logo -->
      <div class="absolute bottom-0 right-0 z-10">
        <img src="{{ asset('assets/Picture-nobg.png') }}" alt="Bottom Logo" class="h-[10rem] opacity-40" />
      </div>

      <!-- Subtle pattern overlay -->
      <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMzAgMTVMMTUgMzAgMzAgNDUgNDUgMzB6IiBzdHJva2U9IiNmZmFjMTAiIHN0cm9rZS13aWR0aD0iMS41IiBmaWxsPSJub25lIi8+PC9zdmc+')]"></div>
    </div>
  </div>
