@extends('layouts.base')
<title>Admin: User Management</title>
@section('content')
<h2>Users</h2>
<form class="row row-cols-lg-auto g-3 align-items-center" action="/admin/users" method="POST">
    @csrf
    <div class="col-12">
        <input name="name" type="text" class="form-control" placeholder="Name">
    </div>
    <div class="col-12">
        <input name="email" type="email" class="form-control" placeholder="Email">
    </div>
    <div class="col-12">
        <input name="password" type="password" class="form-control" placeholder="Password">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input name="admin" class="form-check-input" type="checkbox" id="admincheck">
            <label class="form-check-label" for="admincheck">
                Administrator
            </label>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add user</button>
    </div>
</form>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Admin?</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->admin ? "yes" : "no" }}</td>
            <td>
                <form class="d-none" action="/admin/users/{{$user->id}}" method="POST" id="deleteUser{{$user->id}}Form">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="btn-group">
                    <button type="submit" form="deleteUser{{$user->id}}Form" class="btn btn-sm btn-outline-danger">Delete</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
