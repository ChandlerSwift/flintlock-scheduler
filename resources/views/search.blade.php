@extends('layouts.base')
<title>Search</title>
@section('content')

@if($searchResults->isNotEmpty())
    @foreach ($searchResults as $scout)
        <div class="list">
        <a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>,<a href="/troops/{{$scout->unit}}"> {{ $scout->unit }}</a>
        </div>
    @endforeach
@else 
    <div>  
        <p>No scouts found</p>
    </div>
@endif
@endsection