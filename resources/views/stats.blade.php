@extends('layouts.base')
@section('title', 'Statistics')
@section('content')
<h2>Scouts in sessions</h2>
<table class="table">
    <thead class="table-light">
        <tr>
            <th>Program</th>
        @foreach($weeks as $week)
            <th>{{ $week->name }}</th>
        @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
    @foreach($programs as $program)
        <tr>
            <th>{{ $program->name }}</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->sessions()->where('program_id', $program->id)->get()->reduce(function($carry, $item){ return $carry + $item->scouts()->count(); }) }}/{{ $week->sessions()->where('program_id', $program->id)->get()->reduce(function($carry, $item){ return $carry + $item->program->max_participants; }) }}
            </td>
            @endforeach
            <td>{{ $program->sessions()->get()->reduce(function($carry, $item){ return $carry + $item->scouts()->count(); }) }}/{{ $program->sessions()->get()->reduce(function($carry, $item){ return $carry + $item->program->max_participants; })  }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot class="table-group-divider">
        <tr>
            <th>Total</th>
            @foreach($weeks as $week)
            <td>
                {{ $week->sessions()->get()->reduce(function($carry, $item){ return $carry + $item->scouts()->count(); }) }}/{{ $week->sessions()->get()->reduce(function($carry, $item){ return $carry + $item->program->max_participants; }) }}
            </td>
            @endforeach
            <td>{{ $sessions->reduce(function($carry, $item){ return $carry + $item->scouts()->count(); }) }}/{{ $sessions->reduce(function($carry, $item){ return $carry + $item->program->max_participants; })  }}</td>
        </tr>
    </tfoot>
</table>

<h2>Preferences filled</h2>
<table class="table">
    <thead class="table-light">
        <tr>
            <th>Program</th>
        @foreach($weeks as $week)
            <th>{{ $week->name }}</th>
        @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
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
    <tfoot class="table-group-divider">
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
    <thead class="table-light">
        <tr>
            <th>Program</th>
            @foreach($weeks as $week)
            <th>{{ $week->name }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
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
    <tfoot class="table-group-divider">
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
