<ul>
    @foreach($SupportedLocales as $locale)
        <li>
            <a href="{{ LaravelLocalization::getLocalizedURL($locale['code']) }}">{{ $locale['native'] }}</a></li>
    @endforeach
</ul>