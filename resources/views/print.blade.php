@extends('layouts.base')
<title>Print</title>
@section('content')
<div class="donotprint"> 
    <button class="button" onclick="window.print()">Print All Troops</button>
</div>
@foreach($troops as $troop)
    
    <h1><img src="{{ asset('/mpsclogo.jpeg') }}" height="100px" >Troop {{ $troop }} - {{-- $scouts->first()->site }}, {{ $scouts->first()->subcamp --}}</h1>
    {{-- @foreach($scouts as $scout)
        <h3>{{ $scout->first_name }} {{ $scout->last_name }}</h3>

        <ul>
        @foreach($scout->sessions as $session)
            <li>{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})</li>
        @endforeach
        </ul>
    @endforeach --}}
    <div class="pagebreak"> </div>

@endforeach
@endsection 