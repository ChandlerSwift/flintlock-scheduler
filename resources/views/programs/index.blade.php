@extends('layouts.base')
<title>Programs List</title>

@section('content')
<ul>
    <li><a href="/all_programs">All programs</a></li>
    @foreach($programs as $program)
    <li><a href="/programs/{{$program->id}}">{{ $program->name }}</a></li>
    @endforeach
</ul>
@endsection
