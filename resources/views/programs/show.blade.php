@extends('layouts.base')
<title>{{ $program->name }}</title>
@section('content')
<h1>{{ $program->name }}</h1>
@foreach($program->sessions as $session)

<h3>{{ $session->start_time->format('l') }}  </h3>

<ul>
@foreach($session->scouts as $scout)
    <li><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}) {{ $scout->troop }}, {{ $scout->site }}</a></li>
@endforeach
</ul>
@endforeach
@endsection