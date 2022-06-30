@extends('layouts.base')
@section('content')
<h1>Participation Requirements</h1>
<button type="button">Add new (TODO)</button>
<ul>
    @foreach($reqs as $req)
    <!-- TODO: ", ".join() equivalent -->
    <li>{{ $req->name }} ({{ implode(', ', $req->programs->pluck('name')->all()) }})</li>
    @endforeach
</ul>
@endsection
