@extends('layouts.base')
@section('content')
<div class="list">
@foreach($troops as $troop)
<a href="/flintlock/troops/{{$troop}}">Troop {{ $troop }}</a><br>
@endforeach
</div>
@endsection