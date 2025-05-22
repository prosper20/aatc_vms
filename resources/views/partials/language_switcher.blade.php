<div class="dropdown mt-4">
    <button class="btn btn-light dropdown-toggle text-uppercase" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Language
    </button>
    <ul class="dropdown-menu text-center shadow-sm" aria-labelledby="languageDropdown">
        @foreach($available_locales as $locale_name => $available_locale)
            @if($available_locale === $current_locale)
                <li>
                    <span class="dropdown-item active text-uppercase fw-semibold">{{ $locale_name }}</span>
                </li>
            @else
                <li>
                    <a class="dropdown-item text-uppercase" href="{{ url('language/' . $available_locale) }}">
                        {{ $locale_name }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

