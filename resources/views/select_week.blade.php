@extends('layouts.base')
@section('content')
<h1>Pick a week!</h1>
<ul>
@forelse($weeks as $week)
<li>
    <a href="/weeks/{{ $week->id }}">
        {{ $week->name }}
        ({{ $week->start_date->format('l, Y-m-d') }}&ndash;{{ $week->start_date->addDays(6)->format('l, Y-m-d') }})
    </a>
</li>
@empty
    There are currently no weeks set up.
    @if(Auth::user()->admin)
        Set one up in the <a href="/admin/weeks">Weeks editor</a>.
    @else
        Ask an administrator to set up your week for you.
    @endif
@endforelse
</ul>
@endsection
