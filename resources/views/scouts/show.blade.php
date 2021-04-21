@extends('layouts.base')
<h1>{{ $scout->first_name }} {{ $scout->last_name }}</h1>

<ul>
@foreach($scout->sessions as $session)
    <li>{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})</li>
@endforeach
</ul>
