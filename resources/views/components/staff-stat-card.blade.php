<div class="relative {{ $bgColor }} rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
    <dt>
        <div class="absolute bg-white/10 rounded-xl p-3 flex items-center justify-center">
            <i class="{{ $icon }} {{ $iconColor }} text-lg" aria-hidden="true"></i>
        </div>
        <p class="ml-16 text-sm font-medium {{ $textColor }} truncate">{{ $title }}</p>
    </dt>
    <dd class="ml-16 pb-6 flex items-baseline">
        <p class="text-3xl font-bold {{ $textColor }}">{{ $value }}</p>
        <p class="ml-2 flex items-baseline text-sm font-semibold {{ $getPercentageColorClass() }}">
            {{ $percentage }}
            @if($appendPercentageSymbol)
                <span>%</span>
            @endif
        </p>
    </dd>
</div>
