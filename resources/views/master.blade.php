@extends('layouts.base')
<style>
    table {
        border-collapse: collapse;
    }
    table th, table td {
        border: solid 1px;
    }
</style>
<table>
    <tr>
        <th></th>{{-- offset --}}
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday</th>
        <th>Friday</th>
    </tr>

    @foreach($programs as $program)
    <tr>
        <td style="vertical-align: top;" >{{ $program->name }}</td>
        @foreach($program->sessions->sortBy('start_time') as $session)
            <td style="vertical-align: top;">
                @if($session->running)
                    {{ $session->start_time->format('l, g:i A') }}
                    <ul>
                    @foreach($session->scouts as $scout)
                        <li><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>,
                            <a href="/troops/{{$scout->unit}}"> {{ $scout->unit }}</a></li>
                    @endforeach
                    </ul>
                @else
                    No session scheduled.
                @endif
            </td>
            @if($loop->last){{-- add empty cells for remaining days, if any --}}
                @for($i = $loop->iteration; $i < 5; $i++)
                    <td style="vertical-align: top;">No session scheduled.</td>
                @endfor
            @endif
        @endforeach

    <tr>
    @endforeach
</table>