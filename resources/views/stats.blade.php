@extends('layouts.base')
@section('title', 'Statistics')
@section('content')
<h2>Preferences filled</h2>
<table class="table">
    <thead>
        <tr>
            <th>Program</th>
        @foreach($weeks as $week)
            <th>{{ $week->name }}</th>
        @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    @foreach($programs as $program)
        <tr>
            <th>{{ $program->name }}</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->preferences()->where('program_id', $program->id)->get()->filter(function($pref){ return $pref->satisfied; })->count() }}/{{ $week->preferences()->where('program_id', $program->id)->count() }}
            </td>
            @endforeach
            <td>{{ $preferences->where('program_id', $program->id)->filter(function($pref){ return $pref->satisfied; })->count() }}/{{ $preferences->where('program_id', $program->id)->count() }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->preferences->filter(function($pref){ return $pref->satisfied; })->count() }}/{{ $week->preferences()->count() }}
            </td>
            @endforeach
            <td>
            {{ $preferences->filter(function($pref){ return $pref->satisfied; })->count() }}/{{ $preferences->count() }}
            </td>
        </tr>
    </tfoot>
</table>

@foreach(['Add', 'Drop', 'Swap'] as $action)
<h2>{{ $action }} requests filled</h2>
<table class="table">
    <thead>
        <tr>
            <th>Program</th>
            @foreach($weeks as $week)
            <th>{{ $week->name }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    @foreach($programs as $program)
        <tr>
            <th>{{ $program->name }}</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->changeRequests()->where('action', $action)->where('program_id', $program->id)->whereIn('status', ['archived', 'confirmed'])->count() }}/{{ $week->changeRequests()->where('action', $action)->where('program_id', $program->id)->count() }}
            </td>
            @endforeach
            <td>{{ $changeRequests->where('action', $action)->where('program_id', $program->id)->whereIn('status', ['archived', 'confirmed'])->count() }}/{{ $changeRequests->where('action', $action)->where('program_id', $program->id)->count() }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->changeRequests()->where('action', $action)->whereIn('status', ['archived', 'confirmed'])->count() }}/{{ $week->changeRequests()->where('action', $action)->count() }}
            </td>
            @endforeach
            <td>{{ $changeRequests->where('action', $action)->whereIn('status', ['archived', 'confirmed'])->count() }}/{{ $changeRequests->where('action', $action)->count() }}</td>
        </tr>
    </tfoot>
</table>
@endforeach
@endsection
