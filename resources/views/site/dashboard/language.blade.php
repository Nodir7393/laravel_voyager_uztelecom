<ul class="flex flex-row mt-2">
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <li class="mx-1">
            <a class="hover:text-red-500" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                {{ $properties['native'] }}
            </a>
        </li>
    @endforeach
</ul>
