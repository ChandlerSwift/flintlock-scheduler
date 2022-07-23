@extends('layouts.base')
@section('content')
<h2>Default Sessions</h2>
<form class="row row-cols-lg-auto g-3 align-items-center mt-2" action="/admin/sessions" method="POST">
    @csrf
    <div class="col-12">
        <select class="form-select" name="program_id">
            <option value="test" selected disabled hidden>Choose Program</option>
            @foreach($programs as $program)
            <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select class="form-select" name="start_day">
            <option selected disabled hidden>Choose start day</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
        </select>
    </div>
    <div class="col-12">
        <input name="start_time" type="time" class="form-control" placeholder="Start time">
    </div>
    <div class="col-12">
        <select class="form-select" name="end_day">
            <option selected disabled hidden>Choose end day</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
        </select>
    </div>
    <div class="col-12">
        <input name="end_time" type="time" class="form-control" placeholder="End time">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add session</button>
    </div>
</form>
<table class="table mt-4">
    <thead class="table-dark">
        <tr>
            <th>Program</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sessions as $session)
        <tr>
            <td>{{ $session->program->name }}</td>
            <td>{{ $session->formatted_start_time() }}</td>
            <td>{{ $session->formatted_end_time() }}</td>
            <td>
                <form class="d-none" action="/admin/sessions/{{$session->id}}" method="POST" id="deleteSession{{$session->id}}Form">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="btn-group">
                    <button type="submit" form="deleteSession{{$session->id}}Form" class="btn btn-sm btn-outline-danger">Delete</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
