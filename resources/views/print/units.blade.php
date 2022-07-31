@extends('layouts.base')
@section('content')
    <style>
    div.unitHeader{
        vertical-align:middle;
        display:inline;
        font-size: 12px;
    }
    </style>
    @foreach($units as $unit)
    @if($scouts->where('unit', $unit->unit)->where('council', $unit->council)->pluck('sessions')->collapse()->count() > 0)<!-- skip if there aren't any scouts -->
    <div>
        <img src="{{ asset('/mpsclogo.jpeg') }}" height="100px" style="float: left; margin-right: 1em;">
        <h1 style="padding-top: 1em;">
            Unit {{ $unit->unit }} - {{ $scouts->where('unit', $unit->unit)->where('council', $unit->council)->first()->site }}, {{ $scouts->where('unit', $unit->unit)->where('council', $unit->council)->first()->subcamp }} 
        </h1>
        <p>Times listed are for bus departure time from the {{ $scouts->where('unit', $unit->unit)->where('council', $unit->council)->first()->subcamp }} bus stop.</p>
    </div>
    <br>
        @foreach($scouts->where('unit', $unit->unit)->where('council', $unit->council) as $scout)
            @if($scout->sessions()->count() > 0)
            <div class="nobreak">
                <h3>{{ $scout->first_name }} {{ $scout->last_name }}</h3>
                <ul>
                @foreach($scout->sessions->sortBy('start_time') as $session)
                    <li>{{ $session->program->name == "Fishing Outpost Overnight" ? "Fishing Outpost" : $session->program->name }} ({{ $session->every_day ? "Every day" : $session->start_time->format('l') }}, {{ $bus_times[$session->start_time->format('H:i')][$scouts->where('unit', $unit->unit)->where('council', $unit->council)->first()->subcamp]}})</li>
                @endforeach
                </ul>
            </div>
            @endif
        @endforeach
    <div class="pagebreak"></div>
    @endif
    @endforeach
@endsection 
