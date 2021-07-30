<meta http-equiv="refresh" content="69">
<style>
    * {
        font-family: sans-serif;
        text-decoration: none;
        font-size: 14px;
    }
    table {
        border-collapse: collapse;
        border: solid 1px;
        margin: auto;
        border-color: white;
    }
    th{
        background-color: #333;
        border: solid 1px;
        color: white;
        
    }
    td{
        border: solid 1px;
        border-color: #c9c9c9;
        padding: 5px;
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
    }
    #small tr:nth-child(even){background-color: #f2f2f2;}
    #small td{
        
    }
</style>
<table >
    @foreach($programs as $program)
    <tr>    
        @if ($program->name == 'International Target Sports Outpost')
        <td style="vertical-align: top;font-weight: bold;" >I.T.S.O.</td>
        @else
        <td style="vertical-align: top;font-weight: bold;" >{{ $program->name }}</td>
        @endif
        @foreach($program->sessions->sortBy('start_time') as $session)
            <td style="vertical-align: top;">
                @if($session->running)
                <table id="small">
                    <tr id="small">
                        <th id="small">
                            <b>{{ $session->start_time->format('l') }} - {{ $session->subcamp }}</b>
                        </th>
                        <th>
                        </th>
                        <th>
                        </th>
                        <th>
                        </th>
                    </tr>
                        
                    @foreach($session->scouts->sortBy('troop') as $scout)
                            <tr id="small">
                                <td id="small">{{ $scout->first_name }} {{ $scout->last_name }}</td>
                                <td id="small">{{ $scout->unit }}</td>
                                <td id="small">{{ $scout->gender }}</td>
                                <td id="small">{{ $scout->age }}</td>
                            </tr>
                    @endforeach
                    </table>
                @else
                    
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
