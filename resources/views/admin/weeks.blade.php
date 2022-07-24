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
        <input name="start_date" type="date" class="form-control">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Week</button>
    </div>
</form>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Start date</th>
            <th>Scouts</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($weeks as $week)
        <tr>
            <td>{{ $week->name }}</td>
            <td>{{ $week->start_date->format('l, Y-m-d') }}</td>
            <td>{{ $week->scouts()->count() }}</td>
            <td>
                <div class="input-group">
                <button class="btn btn-sm btn-outline-primary" type="submit" form="plan{{$week->id}}form">Plan</button>
                <form action="/admin/plan_week/{{$week->id}}" method="POST" class="d-none" id="plan{{$week->id}}form">
                    @csrf
                </form>
                <form action="/admin/weeks/{{$week->id}}" method="POST" class="d-none" id="delete{{$week->id}}form">
                    @csrf
                    @method('DELETE')
                </form>
                <button class="btn btn-sm btn-outline-danger" type="submit" form="delete{{$week->id}}form">Delete</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
