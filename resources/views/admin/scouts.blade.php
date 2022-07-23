@extends('layouts.base')

@section('title')
Admin: Week Management
@endsection

@section('content')
<h2 class="mb-5">Weeks</h2>
<h4>Add new week</h4>
<p>
    The starting date is the Sunday that scouts begin arriving. Session slots
    for the week are created when the week is created.
</p>
<form class="mb-5 row row-cols-lg-auto g-3 align-items-center" action="/admin/weeks" method="POST">
    @csrf
    <div class="col-12">
        <input name="name" type="text" class="form-control" placeholder="Name">
    </div>
    <div class="col-12">
        <input name="start_date" type="date" class="form-control" placeholder="Email">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Week</button>
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
