@extends('layouts.base')
<style>
    
    table {
        border-collapse: collapse;
        border: 0px;
        margin: auto;
        width: 100%;
        
    }
    th{
        background-color: #333;
        border: solid 1px;
        color: white;
        
    }
    td{
        border: 0px;
        border-color: white;
        
    }
    #small table{
        border-color: #c9c9c9;
        
    }
    #small th{
        text-align: left;
        color: white;
        padding-top: 12px;
        padding-bottom: 12px;
        padding-left: 7px;
    }
    .program tr:nth-child(even){
        background-color: #f2f2f2;}
    #small td{
        padding: 6px;
    }
</style>
@section('content')

@foreach($programs as $program)
<h2>{{ $program->name }}</h2>
<table>
    <tr>
        @foreach($program->sessions->sortBy('start_time') as $session)
            <td style="vertical-align: top;">
                @if($session->running)
                <table class="program">
                    <tr id="small">
                        <th id="small" colspan="5">{{ $session->start_time->format('l') }} ({{ $session->scouts->count() }}/{{ $session->program->max_participants }})</th>
                    </tr>
                    @foreach($session->scouts->sortBy('troop') as $scout)
                        <tr id="small">
                            <td id="small"><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>@if(!$scout->meetsReqsFor($session->program)) <abbr style="color:red;" title="{{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}">(reqs)</abbr>@endif</td>
                            <td id="small"><a href="/troops/{{$scout->unit}}"> {{ $scout->unit }}</a></td>
                            <td id="small">{{ $scout->gender }}</td>
                            <td id="small">{{ $scout->age }}</td>
                            <td id="small">{{ $scout->subcamp }}</td>
                        </tr>
                    @endforeach
                    </table>
                @else
                <table class="program">
                    <tr id="small">
                        <th id="small">No Session Scheduled</th>
                    </tr>
                </table>
                @endif
            </td>
            @if($loop->last){{-- add empty cells for remaining days, if any --}}
                @for($i = $loop->iteration; $i < 5; $i++)
                    <td style="vertical-align: top;">
                    <table class="program">
                        <tr id="small">
                            <th id="small">No Session Scheduled</th>
                        </tr>
                    </table>
                </td>
                @endfor
            @endif
        @endforeach

    <tr>
</table>
@endforeach
<!-- <table>
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
        <td style="vertical-align: top;font-weight: bold;" >{{ $program->name }}</td>
        @foreach($program->sessions->sortBy('start_time') as $session)
            <td style="vertical-align: top;">
                @if($session->running)
                    @if($session->full)
                        <div style="color:#0d85bd">
                            {{ $session->start_time->format('l, g:i A') }}, &nbsp{{ $session->subcamp }}
                        </div>
                    @else
                    {{ $session->start_time->format('l, g:i A') }}, &nbsp{{ $session->subcamp }}
                    @endif
                    <ul>
                    @foreach($session->scouts->sortBy('troop') as $scout)
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
</table> -->
@endsection
