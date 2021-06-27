@extends('layouts.base')
<title>{{ $program->name }}</title>
@section('content')
<h1>{{ $program->name }}</h1>
@foreach($program->sessions as $session)
    @if($session->scouts->first() == null)
        @continue
    @else
        <h3>{{ $session->start_time->format('l') }}  </h3>

        <ul>
        @foreach($session->scouts as $scout)
            <li><a href="/flintlock/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}) {{ $scout->troop }}, {{ $scout->site }} {{ $scout->troop }}</a></li>
        @endforeach
        </ul>
    @endif
@endforeach
@endsection