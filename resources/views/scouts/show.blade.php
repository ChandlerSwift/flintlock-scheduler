@extends('layouts.base')
<title>Scout</title>
@section('content')
<h1>{{ $scout->first_name }} {{ $scout->last_name }}, {{ $scout->unit }}, {{ $scout->site }}</h1>

<h3>Participation Requirements</h3>
<form action="/scouts/{{ $scout->id }}/participation-requirements" method="post">
    @csrf
    @foreach($scout->satisfiedParticipationRequirements() as $pr)
    <label style="display: block;">
        <input type="checkbox" name="pr[]" value="{{ $pr[0]->id }}" {{ $pr[1] ? "checked" : "" }}> {{ $pr[0]->name }}
    </label>
    @endforeach
    <button type="submit">Save</button>
</form>

<h3>Programs</h3>
<ul>
@foreach($scout->sessions->sortBy('start_time') as $session)
    <li>{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})</li>
@endforeach
</ul>
@endsection
