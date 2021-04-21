@extends('layouts.base')

<ul>
@foreach($troops as $troop)
<li><a href="/troops/{{$troop}}">Troop {{ $troop }}</a></li>
@endforeach
</ul>