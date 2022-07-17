@extends('layouts.base')
@section('content')
<h1>Pick a week!</h1>
<ul>
@foreach($weeks as $week)
<li>
    <a href="/weeks/{{ $week->id }}">
        {{ $week->name }}
        ({{ $week->start_date->format('l, Y-m-d') }}&ndash;{{ $week->start_date->addDays(6)->format('l, Y-m-d') }})
    </a>
</li>
@endforeach
</ul>
@endsection
