@extends('layouts.base')
@section('content')
<div class="search">
    <h1>Search Results:</h1>
    @if($searchResults->isNotEmpty())
        <table class="table">
            <tr>
                <th>Scout</th>
                <th>Unit</th>
                <th>Council</th>
                <th>Site</th>
                <th>Subcamp</th>
                
            </tr>
            @foreach ($searchResults as $scout)
            <tr>
                <td><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></td>
                <td><a href="/units/{{$scout->council}}/{{$scout->unit}}">{{ $scout->unit }}</td>
                <td>{{ $scout->council }}</td>
                <td>{{ $scout->site }}</td>
                <td>{{ $scout->subcamp }}</td>
            </tr>
            @endforeach
        </table>
    @else 
        <div>
            <p>No scouts found</p>
        </div>
    @endif
</div>
@endsection
