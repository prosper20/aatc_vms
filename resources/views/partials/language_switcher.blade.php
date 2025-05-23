@php
    $current_language_name = collect($available_locales)->flip()->get($current_locale, 'Language');
@endphp

<style>
    .dropdown-container {
        position: relative;
        display: inline-block;
        margin-top: 1rem;
    }

    .dropdown-button {
        padding: 0.5rem 1rem;
        background-color: #fff;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        color: #4b5563;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dropdown-button:hover {
        background-color: #f9fafb; /* gray-50 */
    }

    .dropdown-icon {
        width: 1rem;
        height: 1rem;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        background-color: #f1f6f8;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: auto;
        display: none;
        z-index: 1000;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu li {
        list-style: none;
    }

    .dropdown-item {
        display: block;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #4b5563;
        text-transform: uppercase;
        text-decoration: none;
    }

    .dropdown-item:hover {
        background-color: #f3f4f6; /* gray-100 */
    }

    .dropdown-item.active {
        background-color: #f3f4f6;
        font-weight: 600;
    }
</style>

<div class="dropdown-container">
    <button id="languageDropdown" class="dropdown-button" onclick="toggleDropdown()">
        {{ $current_language_name }}
        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <ul id="languageMenu" class="dropdown-menu">
        @foreach($available_locales as $locale_name => $available_locale)
            @if($available_locale === $current_locale)
                <li>
                    <span class="dropdown-item active">{{ $locale_name }}</span>
                </li>
            @else
                <li>
                    <a href="{{ url('language/' . $available_locale) }}" class="dropdown-item">{{ $locale_name }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<script>
    function toggleDropdown() {
        document.getElementById('languageMenu').classList.toggle('show');
    }

    // Optional: Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('languageDropdown');
        const menu = document.getElementById('languageMenu');
        if (!dropdown.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.remove('show');
        }
    });
</script>
