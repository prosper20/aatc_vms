@php
    $current_language_name = collect($available_locales)->flip()->get($current_locale, 'Language');
@endphp

<div class="relative inline-block mt-4">
    <button id="languageDropdown"
        onclick="toggleDropdown()"
        class="px-4 py-2 bg-white rounded-md text-sm font-medium uppercase text-gray-600 flex items-center gap-2 hover:bg-gray-50 transition">
        {{ $current_language_name }}
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <ul id="languageMenu"
        class="absolute right-0 mt-2 bg-[#f1f6f8] border border-black/10 rounded-md shadow-md min-w-max hidden z-50">
        @foreach($available_locales as $locale_name => $available_locale)
            <li>
                @if($available_locale === $current_locale)
                    <span class="block px-4 py-2 text-sm text-gray-600 uppercase font-semibold bg-gray-100">
                        {{ $locale_name }}
                    </span>
                @else
                    <a href="{{ url('language/' . $available_locale) }}"
                        class="block px-4 py-2 text-sm text-gray-600 uppercase hover:bg-gray-100 transition">
                        {{ $locale_name }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</div>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('languageMenu');
        menu.classList.toggle('hidden');
    }

    // Optional: Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const button = document.getElementById('languageDropdown');
        const menu = document.getElementById('languageMenu');

        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
