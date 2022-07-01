@extends('layouts.base')
<title>Print</title>
<style>
div.troopHeader{
    vertical-align:middle;
    display:inline;
    font-size: 12px;
}
</style>

@section('content')
    @foreach($troops as $troop)
    @if($scouts->where('unit', $troop)->pluck('sessions')->collapse()->count() > 0)<!-- skip if there aren't any scouts -->
    <div class="troopHeader">
        <img src="{{ asset('/mpsclogo.jpeg') }}" height="100px" style="float: left; margin-right: 1em;">
        <h1 style="padding-top: 1em;">
            Troop {{ $troop }} - {{ $scouts->where('unit' ,$troop)->first()->site }}, {{ $scouts->where('unit' ,$troop)->first()->subcamp }} 
        </h1>
        <p>Times listed are for bus departure time from the {{ $scouts->where('unit' ,$troop)->first()->subcamp }} bus stop.</p>
    </div>
    <br>
        @foreach($scouts->where('unit', $troop) as $scout)
            @if($scout->sessions->first() == null)
                @continue
            @else
            <h3>{{ $scout->first_name }} {{ $scout->last_name }}</h3>
            <ul>
            @foreach($scout->sessions->sortBy('start_time') as $session)
                <li>{{ $session->program->name == "Fishing Outpost Overnight" ? "Fishing Outpost" : $session->program->name }} ({{ $session->start_time->format('l') }}, {{ $bus_times[$session->start_time->format('H:i')][$scouts->where('unit' ,$troop)->first()->subcamp]}})</li>
            @endforeach
            </ul>
            @endif
        @endforeach
    <div class="pagebreak"> </div>
    @endif
    @endforeach
@endsection 
