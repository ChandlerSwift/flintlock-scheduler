@extends('layouts.base')
<title>Rosters</title>
@section('content')
@foreach($sessions as $session)
    @if($session->scouts->first() == null)
        @continue
    @else
        <h3>{{ $session->program->name }} {{ $session->start_time->format('l') }}</h3>

        <ul>
        @foreach($session->scouts as $scout)
            <li><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}, {{ $scout->age }}), {{ $scout->site }}, {{ $scout->unit }}@if(!$scout->meetsReqsFor($session->program)) (needs {{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}) @endif</a></li>
        @endforeach
        </ul>
    @endif
@endforeach
@endsection 
