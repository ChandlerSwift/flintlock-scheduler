@extends('layouts.base')


@section('head')
<title>Requests</title>
<style>
div.requestTable{
    margin:auto;
    width: 85%;
    margin-top:50px;
    
}
div.requestform{
    background-color: #333;
    margin:auto;
    width: 90%;
    
}
form{
    margin:auto;
}
select.requestform{
    /* blueish*/
    border: none;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
input.notes{
    border: 2px solid white;    
    color: white;
    background-color: #333;
    font-size: 16px;
    box-sizing: border-box;
    padding: 12px 20px;
    width: 20%;
}
table {
        width:100%;
}
</style>
@endsection

@section('content')
<div class="requestform">
    <form action="/requests" method="POST">
        @csrf
        <select  class="requestform" id="addDrop" name="addDrop">
            <option value="addDrop" selected disabled hidden>Add or Drop</option>  
            <option value="Add">Add</option>
            <option value="Drop">Drop</option>
        </select>
        <select class="requestform" id="troop" name="Troop">
            <option value="test" selected disabled hidden>Choose Troop</option>
            @foreach($troops as $troop)
                <option value="{{ $troop }}">{{ $troop }} 
                @if ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Buckskin')
                    (B)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Ten Chiefs')
                    (TC)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Voyageur')
                    (V)
                @else

                @endif
                </option>
            @endforeach
        </select>
        <select class="requestform" id="scout" name="Scout" disabled>
        <option value="test" selected disabled hidden>Choose Scout</option>  
            @foreach($scouts->sortby('last_name') as $scout)
                <option value="{{ $scout->id }}" data-troop="{{ $scout->unit }}">{{ $scout->first_name }} {{ $scout->last_name }}</option>
            @endforeach
        </select>
        <select  class="requestform" id="program" name="program">
            <option value="test" selected disabled hidden>Choose Program</option>  
            @foreach($programs as $program)
                <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select>
    <input class="notes" id="notes" name="notes"placeholder="Notes">
    <button class="button" onclick="">Submit Request</button>
    </form>
</div>
<script>
let currentValue = document.getElementById("troop").value;
if (currentValue == "test") {
    document.getElementById("scout").disabled = true;
} else {
    document.getElementById("scout").disabled = false;
    document.querySelectorAll("select#scout > option").forEach(function(o){
        o.hidden = o.dataset['troop'] != currentValue;
    });
}

document.getElementById("troop").addEventListener("change", function(e){
    document.getElementById('scout').value = "test";
    if (e.target.value !== "test") {
        document.getElementById("scout").disabled = false;
        document.querySelectorAll("select#scout > option").forEach(function(o){
            o.hidden = o.dataset['troop'] != e.target.value;
        });
    } // else it's the "Choose Troop" option
});
</script>
<div class="requestTable">
    <h3>Active Requests</h3>
    <table>
        <tr>
            <th>Updated At</th>
            <th>Scout</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Status</th>
        </tr>
        @foreach ($changeRequests->where('status', 'scheduled') as $changeRequest)
            <tr>
                <td>{{ $changeRequest->updated_at->format('l, g:i A')}}</td>
                <td>{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</td>
                <td>{{ $changeRequest->scout->unit }}</td>
                <td>{{ $changeRequest->program->name }}</td>
                @if ($changeRequest->session != null)
                    <td>{{ $changeRequest->session->start_time->format('l, g:i A') }}</td>
                @else
                    <td></td>
                @endif
                <td>{{ $changeRequest->action }}</td>
                <td>{{ $changeRequest->notes }}</td>
                <td>
                <a style="color: blue;" 
                    href="/requests" 
                    onclick="return confirm('CONFIRM {{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }} for {{ $changeRequest->program->name }}?');" >
                    CONFIRM
                    </a>
                </td>
            </tr>
        @endforeach
        @foreach ($changeRequests->where('status', 'pending') as $changeRequest)
            <tr>
                <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
                <td>{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</td>
                <td>{{ $changeRequest->scout->unit }}
                @if ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Buckskin')
                    (B)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Ten Chiefs')
                    (TC)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Voyageur')
                    (V)
                @else

                @endif</td>
                <td>{{ $changeRequest->program->name }}</td>
                @if ($changeRequest->session != null)
                    <td>{{ $changeRequest->session->start_time->format('l, g:i A') }}</td>
                @else
                <td>
                        <select id="session" name="session">
                            <option value="test" selected disabled hidden>Choose Session</option>  
                            @foreach($changeRequest->program->sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->start_time->format('l') }}</option>
                            @endforeach
                        </select>
                    </td>
                @endif
                <td>{{ $changeRequest->action }}</td>
                <td>{{ $changeRequest->notes }}</td>
                <td>
                    <a style="color: blue;" 
                    href="/requests" 
                    onclick="return confirm('APPROVE {{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }} for {{ $changeRequest->program->name }}?');" >
                    APPROVE
                    </a>
                </td>
            </tr>
        @endforeach
        @foreach ($changeRequests->where('status', 'approved') as $changeRequest)
            <tr>
                <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
                <td>{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</td>
                <td>{{ $changeRequest->scout->unit }}
                @if ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Buckskin')
                    (B)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Ten Chiefs')
                    (TC)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Voyageur')
                    (V)
                @else

                @endif</td>
                <td>{{ $changeRequest->program->name }}</td>
                <td>{{ $changeRequest->session->start_time->format('l, g:i A') }}</td>
                <td>{{ $changeRequest->action }}</td>
                <td>{{ $changeRequest->notes }}</td>
                <td>Approved</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="requestTable">
    <h3>Archived Requests</h3>
    <table>
        <tr>
            <th>Confirmed At</th>
            <th>Scout</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Status</th>
        </tr>
        @foreach ($changeRequests->where('status', 'archived') as $changeRequest)
            <tr>
                <td>{{ $changeRequest->updated_at->format('l, g:i A')}}</td>
                <td>{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</td>
                <td>{{ $changeRequest->scout->unit }}
                @if ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Buckskin')
                    (B)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Ten Chiefs')
                    (TC)
                @elseif ( $scouts->where('unit' ,$troop)->first()->subcamp  == 'Voyageur')
                    (V)
                @else

                @endif</td>
                <td>{{ $changeRequest->program->name }}</td>
                @if ($changeRequest->session != null)
                    <td>{{ $changeRequest->session->start_time->format('l, g:i A') }}</td>
                @else
                    <td></td>
                @endif
                <td>{{ $changeRequest->action }}</td>
                <td>{{ $changeRequest->notes }}</td>
                <td>Archived</td>
            </tr>
        @endforeach
    </table>
    <br>
    <br>
    <br>
    <br>
    <h4> Status Meanings:</h4>
    <p> Pending - Waiting on Flintlock Approval (Someone may have to drop to make this request happen)<br>
        Approved/CONFIRM? - Waiting on Subcamp PD to confirm the scheduled timeslot<br>
        Confirmed (In the archived table) - Subcamp has confirmed and Scout is scheduled
    </p>
</div>
@endsection