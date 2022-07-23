@extends('layouts.base')
<title>Admin: Manage Programs</title>
@section('content')
<h2>Programs</h2>
<form class="row row-cols-lg-auto g-3 align-items-center" action="/admin/programs" method="POST">
    @csrf
    <div class="col-12">
        <input name="name" type="text" class="form-control" placeholder="Name">
    </div>
    <div class="col-12">
        <input name="max_participants" type="number" class="form-control" placeholder="Max participants">
    </div>
    <div class="col-12">
        <input name="min_scout_age" type="number" class="form-control" placeholder="Minimum scout age">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add program</button>
    </div>
</form>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Max participants</th>
            <th>Minimum age</th>
            <th>Participation Requirements (<a style="color:white;" href="/admin/participation-requirements">edit</a>)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programs as $program)
        <tr>
            <td>{{ $program->name }}</td>
            <td>{{ $program->max_participants }}</td>
            <td>{{ $program->min_scout_age }}</td>
            <td>{{ implode(', ', $program->participationRequirements->pluck('name')->all()) }}</td>
            <td>
                <form class="d-none" action="/admin/programs/{{$program->id}}" method="POST" id="deleteProgram{{$program->id}}Form">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="btn-group">
                    <button type="submit" form="deleteProgram{{$program->id}}Form" class="btn btn-sm btn-outline-danger">Delete</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
