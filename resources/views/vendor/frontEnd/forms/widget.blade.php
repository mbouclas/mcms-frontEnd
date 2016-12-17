
<form method="post" action="{{ $actionUrl }}">
    {{ csrf_field() }}
    <h1>{{ $Form['label'][$locale] }}</h1>
    <p>{{ $Form['description'][$locale] }}</p>

    <input type="hidden" name="form" value="{{ $Form['slug'] }}">
    @foreach($Form['fields'] as $field)
        <div class="form-group">
            <label for="exampleInputEmail1">{!! $field['label'][$locale] !!}</label>
            <input type="{{ $field['type'] }}"
                   class="form-control"
                   id="{{ $field['varName'] }}"
                   name="{{ $field['varName'] }}"
                   placeholder="{!! $field['label'][$locale] !!}">
        </div>
    @endforeach
    <button type="submit" class="btn btn-default">Submit</button>
</form>
