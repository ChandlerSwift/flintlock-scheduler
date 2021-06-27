@extends('layouts.base')
<title>Search</title>
<style>
div.search{
    margin:auto;
    width: 65%;
    margin-top:50px;
}
table {
        width:100%;
}

</style>
@section('content')
<div class="search">
    <h1> Search Results:</h1>
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
                <td><a href="/flintlock/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></td>
                <td><a href="/flintlock/troops/{{$scout->unit}}"> {{ $scout->unit }}</td>
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