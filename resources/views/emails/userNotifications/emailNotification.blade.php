@extends('layouts.emails')

@section('content')
    <div>
        {!! $item->body !!}
    </div>

@endsection