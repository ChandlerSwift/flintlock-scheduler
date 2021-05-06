@extends('layouts.base')
<title>Print</title>
@section('content')
<div class="donotprint"> 
    <button onclick="window.print()">Print All Troops</button>
</div>
@foreach($troops as $troop)
    <h1>Troop {{ $troop }}  </h1>
    @foreach($scouts as $scout)
        <h3><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></h3>

        <ul>
        @foreach($scout->sessions as $session)
            <li>{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})</li>
        @endforeach
        </ul>
    @endforeach
@endforeach
@endsection 