@extends('layouts.base')
<style>
    
    #big table {
        border-collapse: collapse;
        border: solid 1px;
        margin: auto;
        border-color: white;
        width: 100%;
        
    }
    #big th{
        background-color: #333;
        border: solid 1px;
        color: white;
        
    }
    #big td{
        border: solid 1px;
        border-color: white;
        
    }
    #small table{
        border-color: #c9c9c9;  
        
    }
    #small th{
        border: solid 1px;
        border-color: #007ec7;
        text-align: left;
        background-color: #007ec7;
        color: white;
        padding-top: 12px;
        padding-bottom: 12px;
        padding-left: 7px;
    }
    #small tr:nth-child(even){background-color: #f2f2f2;}
    #small td{
        padding: 6px
    ;
    }
</style>
@section('content')

<table id="big">
    @foreach($programs as $program)
    <tr>    
        @if ($program->name == 'International Target Sports Outpost')
        <td style="vertical-align: top;font-weight: bold;" >
        <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>I.T.S.O.</b>
                        </th>
                    </tr>
                </table>
        </td>
        @else
        <td style="vertical-align: top;font-weight: bold;" >
        <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>{{$program->name}}</b>
                        </th>
                    </tr>
                </table>
        </td>
        @endif
        @foreach($program->sessions->sortBy('start_time') as $session)
            <td style="vertical-align: top;">
                @if($session->running)
                <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>{{ $session->start_time->format('l') }}
                            </b>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($session->scouts->sortBy('troop') as $scout)
                            <tr id="small">
                                <td id="small"><a href="/flintlock/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a></td>
                                <td id="small"><a href="/flintlock/troops/{{$scout->unit}}"> {{ $scout->unit }}</a></td>
                                <td id="small">{{ $scout->gender }}</td>
                                <td id="small">{{ $scout->age }}</td>
                                <td id="small">{{ $scout->subcamp }}</td>
                            </tr>
                    @endforeach
                    </table>
                @else
                <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>No Session Scheduled</b>
                        </th>
                    </tr>
                </table>
                @endif
            </td>
            @if($loop->last){{-- add empty cells for remaining days, if any --}}
                @for($i = $loop->iteration; $i < 5; $i++)
                    <td style="vertical-align: top;">
                    <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>No Session Scheduled</b>
                        </th>
                    </tr>
                </table>
                </td>
                @endfor
            @endif
        @endforeach

    <tr>
    @endforeach
</table>
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
                        <li><a href="/flintlock/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>,
                            <a href="/flintlock/troops/{{$scout->unit}}"> {{ $scout->unit }}</a></li>
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