@extends('layouts.app')

@section('content')
<div class="container min-w-full">
    <!-- Header Section -->
<div class="py-4 px-6 mb-6 my-6 ">
    <div class="flex items-center justify-between">
        <!-- Logo -->
        <img src="{{ asset('assets/logo-green-yellow.png') }}" alt={{__("Logo")}} class="h-10 md:h-12">

        <!-- Hamburger Icon for Mobile -->
        <button id={{__("menuToggle")}} class="md:hidden text-gray-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
            @include('partials.language_switcher')

            <div class="text-right mt-4 md:mt-0">
                <div><strong>{{ $staff->name }}</strong></div>
                <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
                @csrf
                <button
                    class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Collapsible Menu -->
    <div id="mobileMenu" class="md:hidden mt-4 md:mt-0 md:flex md:items-center md:justify-end md:gap-4 hidden md:block">
        @include('partials.language_switcher')

        <div class="text-right mt-4 md:mt-0">
            <div><strong>{{ $staff->name }}</strong></div>
            <div>{{ __('Location') }}: <strong>{{ __('Abuja') }}</strong></div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 md:mt-0">
            @csrf
            <button
                class="bg-gray-400 md:bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-4 rounded w-full md:w-auto">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>


    <!-- top -->

    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="w-full md:min-h-[250px] rounded-xl flex flex-col lg:flex-row gap-6 bg-transparent md:bg-[#07AF8B]">
          <!-- Left Column -->
          <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-[#07AF8B] md:bg-transparent rounded-xl  p-6 shadow-md md:shadow-none flex justify-center items-end min-h-[180px]">
                <div class="text-white text-left max-w-full px-4">
                  <h2 class="text-3xl font-bold mb-2">{{__("Book visitors into Abuja AATC facilities")}}</h2>
                  <p class="text-lg mb-4 leading-relaxed">{{__("Request for entry permit for business associates and partners from the comfort of your office and get real-time notifications on the progress of your request.")}}</p>
                  <div class="block md:inline-block text-center w-full md:w-auto ">
                    <a href="{{ route('register_visitor') }}" class="bg-[#FFCA00] hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg transition-colors w-full md:w-auto inline-block">
                        {{__("New Request")}}
                    </a>
                  </div>
                </div>
              </div>
          </div>

          <!-- Right Column -->
          <div class="w-full lg:w-1/3 md:flex md:items-center md:justify-center md:pr-6">
            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-[#FFCA00] text-black rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['total_requests'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Total Requests') }}</p>
                </div>
                <div class="bg-[#07AF8B] md:bg-[#05896D] text-white rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['approved'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Approved') }}</p>
                </div>
                <div class="bg-[#6c757d] text-white rounded-xl p-6 shadow-md text-center">
                  <h1 class="text-3xl font-bold">{{ $stats['declined'] ?? 0 }}</h1>
                  <p class="text-sm mt-2">{{ __('Declined') }}</p>
                </div>
              </div>
            </div>

          </div>
      </div>

    <!-- bottom -->

    <div class="w-full py-6 px-4 md:px-8 bg-transparent">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Left Column -->
          <div class="w-full lg:w-1/2 space-y-6">

            {{-- NOTIFICATIONS --}}
            <div class="bg-white rounded-xl shadow-md p-6">
              <h2 class="text-lg font-semibold mb-4">{{ __('Notifications') }}</h2>
              @if ($notifications->isEmpty())
    <div class="text-center text-gray-500 text-sm">
      {{ __('No notifications yet.') }}
    </div>
  @else
    <ul class="space-y-3">
                @foreach ($notifications as $note)
                  <li class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-2">
                      <span class="material-icons text-[#07AF8B] text-base">
                        {{ $note->status === 'approved' ? 'check_circle' : 'cancel' }}
                      </span>
                      <span>{{ $note->name }} {{ __($note->status) }}.</span>
                    </div>
                    <span class="text-gray-500">{{ $note->updated_at->format('g:iA d/m/Y') }}</span>
                  </li>
                @endforeach
              </ul>
  @endif
            </div>
          </div>

          <!-- Right Column -->
          <div class="container">

            {{-- SEARCH + REQUEST TABLE --}}
            <div class="bg-white rounded-xl shadow-md p-6">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h2 class="text-lg font-semibold">{{ __('My Requests') }}</h2>
                <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                  <input type="text" name="search" class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="{{ __('Search visitors...') }}" value="{{ $search }}">
                  <div class="flex gap-2">
                    <button type="submit" class="bg-[#FFCA00] hover:bg-yellow-400 text-black font-bold px-4 py-2 rounded-lg text-sm">{{ __('Search') }}</button>
                    @if($search)
                      <a href="{{ route('home') }}" class="text-[#07AF8B] text-sm">{{ __('Clear') }}</a>
                    @endif
                  </div>
                </form>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="bg-[#07AF8B] text-white">
                      <th class="text-left p-3">{{ __('Visitor') }}</th>
                      <th class="text-left p-3">{{ __('Date') }}</th>
                      <th class="text-left p-3">{{ __('Purpose') }}</th>
                      <th class="text-left p-3">{{ __('Status') }}</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse ($requests as $request)
                      <tr class="hover:bg-gray-50 cursor-pointer" onclick="openModal({{ $request->id }})">
                        <td class="p-3">{{ $request->visitor->name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $request->unique_code }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($request->visit_date)->format('d/m/Y g:i A') }}</td>
                        <td class="p-3">
                          @php $status = strtolower($request->status); @endphp
                          @if($status === 'approved')
                            <span class="text-[#07AF8B] font-semibold">{{ __('Approved') }}</span>
                          @elseif($status === 'pending')
                            <span class="text-[#FFA500] font-semibold">{{ __('Pending') }}</span>
                          @elseif(in_array($status, ['declined', 'rejected']))
                            <span class="text-[#b00020] font-semibold">{{ __('Declined') }}</span>
                          @else
                            <span>{{ __($request->status) }}</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">{{ __('No requests found.') }}</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        </div>
      </div>

</div>
<script>
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

@endsection
