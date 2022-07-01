@extends('layouts.base')
@section('content')
<h1>Needed Participation Requirements&mdash;{{ $subcamp }}</h1>

<form method="post">
    @csrf
    @foreach($reqs as $req)
    <h3>{{ $req->name }}</h3>
    @foreach($scouts as $scout)
    @if($scout->needs($req))
    <label>
        <input type="checkbox" name="{{ $scout->id }}-{{ $req->id }}" @if($scout->participationRequirements->contains($req))checked @endif>
        {{ $scout->first_name }} {{ $scout->last_name }}, {{ $scout->site }}, {{ $scout->unit }}
    </label>
    <br>
    @endif
    @endforeach
    @endforeach
    <br>
    <button type="submit">Update requirements</button>
</form>
@endsection
