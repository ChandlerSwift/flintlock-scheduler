@extends('layouts.base')
@section('content')
<div class="list">
@foreach($troops as $troop)
<a href="/troops/{{$troop}}">Troop {{ $troop }}</a><br>
@endforeach
</div>
@endsection