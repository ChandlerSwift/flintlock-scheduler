@extends('layouts.base')

@section('title')
Admin: Scout Management
@endsection

@section('content')
<h2>Scouts</h2>
<p>This only includes scouts for week "{{ \App\Models\Week::find(request()->cookie('week_id'))->name }}".</p>
<h4>Add new scout</h4>

<form class="mb-5 row row-cols-lg-auto g-3 align-items-center" action="/admin/scouts" method="POST">
    @csrf
    <input type="hidden" name="week_id" value="{{ request()->cookie('week_id') }}">
    <div class="col-12">
        <input name="first_name" type="text" class="form-control" placeholder="First name">
    </div>
    <div class="col-12">
        <input name="last_name" type="text" class="form-control" placeholder="Last name">
    </div>
    <div class="col-12">
        <select class="form-select" name="rank">
            <option selected disabled hidden>Rank</option>
            <option value="0">Scout</option>
            <option value="1">Tenderfoot</option>
            <option value="2">Second class</option>
            <option value="3">First class</option>
            <option value="4">Star</option>
            <option value="5">Life</option>
            <option value="6">Eagle</option>
        </select>
    </div>
    <div class="col-12">
        <input name="age" type="number" class="form-control" placeholder="Age">
    </div>
    <div class="col-12">
        <input name="gender" type="text" class="form-control" placeholder="Gender">
    </div>
    <div class="col-12">
        <input name="unit" type="text" class="form-control" placeholder="Unit">
    </div>
    <div class="col-12">
        <input name="council" type="text" class="form-control" placeholder="Council">
    </div>
    <div class="col-12">
        <input name="site" type="text" class="form-control" placeholder="Site">
    </div>
    <div class="col-12">
        <select class="form-select" name="subcamp">
            <option selected disabled hidden>Subcamp</option>
            <option value="Buckskin">Buckskin</option>
            <option value="Ten Chiefs">Ten Chiefs</option>
            <option value="Voyageur">Voyageur</option>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Scout</button>
    </div>
</form>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th>First name</th>
            <th>Last Name</th>
            <th>Rank</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Unit</th>
            <th>Council</th>
            <th>Site</th>
            <th>Subcamp</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scouts as $scout)
        <tr>
            <td>{{ $scout->first_name }}</td>
            <td>{{ $scout->last_name }}</td>
            <td>{{ $scout->rank }}</td>
            <td>{{ $scout->age }}</td>
            <td>{{ $scout->gender }}</td>
            <td>{{ $scout->unit }}</td>
            <td>{{ $scout->council }}</td>
            <td>{{ $scout->site }}</td>
            <td>{{ $scout->subcamp }}</td>
            <td>
                <div class="input-group">
                <button class="btn btn-sm btn-outline-danger" type="submit" form="delete{{$scout->id}}form">Delete</button>
                <form action="/admin/scouts/{{$scout->id}}" method="POST" class="d-none" id="delete{{$scout->id}}form">
                    @csrf
                    @method('DELETE')
                </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
