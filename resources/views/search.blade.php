@extends('layouts.base')
@section('content')
<div class="search">
    <h1>Search Results:</h1>
    @if($searchResults->isNotEmpty())
        <table>
            <tr>
                <th>Scout</th>
                <th>Troop</th>
                <th>Site</th>
                <th>Subcamp</th>
                
            </tr>
            @foreach ($searchResults as $scout)
            <tr>
                <td><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></td>
                <td><a href="/troops/{{$scout->unit}}"> {{ $scout->unit }}</td>
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
