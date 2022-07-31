@extends('layouts.base')
@section('content')
<div class="list">
@foreach($units as $unit)
<a href="/units/{{$unit}}">Unit {{ $unit }}</a><br>
@endforeach
</div>
@endsection
