@extends('layouts.base')
<title>{{ $program->name }}</title>
@section('content')
<h1>{{ $program->name }}</h1>
@foreach($program->sessions()->where('week_id', request()->cookie('week_id'))->get() as $session)
    @if($session->scouts->first() == null)
        @continue
    @else
        @if(!$session->every_day)
        <h3>{{ $session->start_time->format('l') }}  </h3>
        @endif

        <ul>
        @foreach($session->scouts as $scout)
            <li><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}), {{ $scout->site }}, {{ $scout->unit }}</a></li>
        @endforeach
        </ul>
    @endif
@endforeach
@endsection
