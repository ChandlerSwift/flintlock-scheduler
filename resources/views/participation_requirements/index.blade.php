@extends('layouts.base')
@section('content')
<h1>Participation Requirements</h1>
<ul>
    @foreach($reqs as $req)
    <li>{{ $req->name }} ({{ implode(', ', $req->programs->pluck('name')->all()) ?: "no programs require this" }})</li>
    @endforeach
</ul>
<h3>Add new requirement</h3>
<form method="post">
    @csrf
    <input type="text" name="name" placeholder="New requirement name">
    <button type="submit">Add new</button>
</form>
@endsection
