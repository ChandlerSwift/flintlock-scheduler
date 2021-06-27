@extends('layouts.base')
<title>Programs List</title>

@section('content')
<div class="list">
    @foreach($programs as $program)
    <a href="/flintlock/programs/{{$program->id}}">{{ $program->name }}</a><br>
    @endforeach
</div>
@endsection
