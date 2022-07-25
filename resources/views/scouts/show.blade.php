@extends('layouts.base')
<title>Scout</title>
@section('content')
<h1>{{ $scout->first_name }} {{ $scout->last_name }}, {{ $scout->unit }}, {{ $scout->site }}</h1>

<h3>Participation Requirements</h3>
<form action="/scouts/{{ $scout->id }}/participation-requirements" method="post">
    @csrf
    @foreach($scout->satisfiedParticipationRequirements() as $pr)
    <label style="display: block;">
        <input class="form-check-input" type="checkbox" name="pr[]" value="{{ $pr[0]->id }}" {{ $pr[1] ? "checked" : "" }}> {{ $pr[0]->name }}
    </label>
    @endforeach
    <button class="btn btn-outline-primary" type="submit">Save</button>
</form>

<h3>Sessions</h3>
@if(Auth::user()->admin)
<form action="/admin/scouts/{{ $scout->id }}/addSession" method="POST">
    @csrf
    <div class="input-group mb-3">
        <select name="session_id" required class="form-select">
            <option selected disabled hidden>Choose a Session</option>
            @foreach($sessions as $session)
            <option value="{{ $session->id }}">{{ $session->program->name }} ({{ $session->start_time->format('l, g:i A') }})</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-primary">Add session</button>
    </div>
</form>
@endif
<ul>
@foreach($scout->sessions->sortBy('start_time') as $session)
    <li>
        {{ $session->program->name }}
        ({{ $session->start_time->format('l, g:i A') }}&ndash;{{ $session->end_time->format('l, g:i A') }})
        @if(Auth::user()->admin)
        <form class="d-none" id="dropSession{{ $session->id }}form" action="/admin/scouts/{{ $scout->id }}/dropSession/{{ $session->id }}" method="post">
            @csrf
        </form>
        <button class="btn btn-sm btn-outline-danger" form="dropSession{{ $session->id }}form">Drop</button>
        @endif
    </li>
@endforeach
</ul>
@endsection
