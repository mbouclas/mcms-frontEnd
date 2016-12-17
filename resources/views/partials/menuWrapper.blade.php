<li @if (count($item['children']) > 0)class="parent"@endif>
    <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), ($item->link ?: $item->permalink)) }}"
       class="{{ \FrontEnd\Helpers\ActiveStates::set_active($item->permalink ?: $item->link) }}"
       title="{!! $item->title !!}">{!! $item->title !!}</a>
    @if (count($item['children']) < 1)
</li>
    @endif

@if (count($item['children']) > 0)
        <ul class="submenu">
            @foreach($item['children'] as $child)
                @include('partials.subMenuItem', $child)
            @endforeach
        </ul>

@endif
