@extends('layouts.base')
@section('content')
<div class="list">
@foreach($units as $unit)
<a href="/units/{{$unit->council}}/{{$unit->unit}}">Unit {{ $unit->unit }}</a><br>
@endforeach
</div>
@endsection
