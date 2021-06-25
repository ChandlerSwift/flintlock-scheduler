@extends('layouts.base')
<title>Print</title>
<style>
div.troopHeader{
    vertical-align:middle;
    display:inline;
}
</style>
@section('content')
<div class="donotprint"> 
    <button class="button" onclick="window.print()">Print All Troops</button>
</div>
{{$troopHasScouts = false}}
@foreach($troops as $troop)
    
        <div class="troopHeader">
            <h1>
                <img src="{{ asset('/mpsclogo.jpeg') }}" height="100px" style='vertical-align:middle;' >
                Troop {{ $troop }} - {{ $scouts->where('unit' ,$troop)->first()->site }}, {{ $scouts->where('unit' ,$troop)->first()->subcamp }} 
            </h1>
        </div>
        <br>
            @foreach($scouts->where('unit', $troop) as $scout)
                @if($scout->sessions->first() == null)
                    @continue
                @else
                <h3>{{ $scout->first_name }} {{ $scout->last_name }}</h3>
                <ul>
                @foreach($scout->sessions->sortBy('start_time') as $session)
                    <li>{{ $session->program->name }} ({{ $session->start_time->format('l') }})</li>
                @endforeach
                </ul>
                @endif
            @endforeach
        <div class="pagebreak"> </div>
@endforeach
@endsection 