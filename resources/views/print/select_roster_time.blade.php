@extends('layouts.base')
<title>Rosters</title>
@section('content')
<h1>Select rosters to print</h1>
<form method="post">
    @csrf
    @foreach($start_times as $start_time)
    <label style="display: block;">
        <input type="checkbox" name="start_times[]" value="{{ $start_time->timestamp }}"> {{ $start_time->format('l, g:i A') }}
    </label>
    @endforeach
    <button type="submit">Save</button>
</form>
@endsection 
