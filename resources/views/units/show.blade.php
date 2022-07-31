@extends('layouts.base')
@section('content')
<h1>Unit {{ $unit }} ({{ $scouts->first()->site }}, {{ $scouts->first()->subcamp }})</h1>
@foreach($scouts as $scout)
    @if($scout->sessions->first() == null)
        @continue
    @else
    <h3><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></h3>

    <ul>
    
    @foreach($scout->sessions->sortBy('start_time') as $session)
        <li>{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})</li>
    @endforeach
    </ul>
    @endif
@endforeach
@endsection
