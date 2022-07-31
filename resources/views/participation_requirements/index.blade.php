@extends('layouts.base')
@section('content')
<h1>Participation Requirements</h1>
<h3 class="mt-5">Add new requirement</h3>
<form method="post">
    @csrf
    <div class="input-group" style="max-width: 600px;">
        <input class="form-control" type="text" name="name" placeholder="New requirement name">
        <button class="btn btn-outline-primary" type="submit">Add</button>
    </div>
</form>
<h3 class="mt-5">Edit program requirements</h3>
<form method="post" action="/admin/participation-requirements/sync">
    @csrf
    @foreach($reqs as $req)
    <h5>{{ $req->name }}</h5>
    @foreach($programs as $program)
    <label>
        <input type="checkbox" name="{{ $program->id }}-{{ $req->id }}" @if($program->participationRequirements->contains($req))checked @endif>
        {{ $program->name }}
    </label>
    <br>
    @endforeach
    <br>
    @endforeach
    <button class="btn btn-outline-primary type="submit">Update requirements</button>
</form>
@endsection
