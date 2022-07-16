@extends('layouts.base')

@section('content')
<h3>New request</h3>
<form class="row row-cols-lg-auto g-3 align-items-center bg-light rounded" action="/requests" method="POST">
    @csrf
    <div class="col-12">
        <select class="form-select" name="addDrop">
            <option value="addDrop" selected disabled hidden>Add or Drop</option>
            <option value="Add">Add</option>
            <option value="Drop">Drop</option>
        </select>
    </div>

    <div class="col-12">
        <select class="form-select" id="troop">
            <option value="test" selected disabled hidden>Choose Troop</option>
            @foreach($troops as $troop)
            <option value="{{ $troop }}">{{ $troop }}
                @if ( $scouts->where('unit', $troop)->first()->subcamp == 'Buckskin')
                (B)
                @elseif ( $scouts->where('unit', $troop)->first()->subcamp == 'Ten Chiefs')
                (TC)
                @elseif ( $scouts->where('unit', $troop)->first()->subcamp == 'Voyageur')
                (V)
                @endif
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <select class="form-select" id="scout" name="Scout" disabled>
            <option value="test" selected disabled hidden>Choose Scout</option>
            @foreach($scouts->sortby('last_name') as $scout)
            <option value="{{ $scout->id }}" data-troop="{{ $scout->unit }}">{{ $scout->first_name }} {{ $scout->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select class="form-select" id="program">
            <option value="test" selected disabled hidden>Choose Program</option>
            @foreach($programs as $program)
            <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select class="form-select" id="session" name="session">
            <option value="test" selected disabled hidden>Session</option>
            @foreach($sessions as $session)
            <option value="{{ $session->id }}" data-program="{{ $session->program_id }}">{{ $session->start_time->format('l, g:i A') }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <input class="form-control" name="notes" placeholder="Notes">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

</form>
<script>
    let currentValue = document.getElementById("troop").value;
    if (currentValue == "test") {
        document.getElementById("scout").disabled = true;
    } else {
        document.getElementById("scout").disabled = false;
        document.querySelectorAll("select#scout > option").forEach(function(o) {
            o.hidden = o.dataset['troop'] != currentValue;
        });
    }

    document.getElementById("program").addEventListener("change", function(e) {
        document.getElementById('session').value = "test";
        if (e.target.value !== "test") {
            document.getElementById("session").disabled = false;
            document.querySelectorAll("select#session > option").forEach(function(o) {
                o.hidden = o.dataset['program'] != e.target.value;
            });
        } // else it's the "Choose Troop"
    });

    let programCurrentValue = document.getElementById("program").value;
    if (programCurrentValue == "test") {
        document.getElementById("session").disabled = true;
    } else {
        document.getElementById("session").disabled = false;
        document.querySelectorAll("select#scout > option").forEach(function(o) {
            o.hidden = o.dataset['program'] != currentValue;
        });
    }

    document.getElementById("troop").addEventListener("change", function(e) {
        document.getElementById('scout').value = "test";
        if (e.target.value !== "test") {
            document.getElementById("scout").disabled = false;
            document.querySelectorAll("select#scout > option").forEach(function(o) {
                o.hidden = o.dataset['troop'] != e.target.value;
            });
        }
    });
</script>

<h3 class="mt-5">Pending Flintlock Approval</h3>
<table class="table table-bordered table-sm">
    <thead class="table-dark">
        <tr>
            <th>Created At</th>
            <th>Scout</th>
            <th>Age</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Reqs</th>
            <th>Status</th>
        </tr>
    </thead>
    @foreach ($changeRequests->sortBy('created_at')->where('status', 'pending') as $changeRequest)
    <tr>
        <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
        <td><a href="/scouts/{{ $changeRequest->scout->id}}">{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</a></td>
        <td>{{ $changeRequest->scout->age }}</td>
        <td><a href="/troops/{{$changeRequest->scout->unit}}">{{ $changeRequest->scout->unit }}
                @if ($changeRequest->scout->subcamp == 'Buckskin')
                (B)
                @elseif ($changeRequest->scout->subcamp == 'Ten Chiefs')
                (TC)
                @elseif ($changeRequest->scout->subcamp == 'Voyageur')
                (V)
                @else
                @endif</a></td>
        <td>{{ $changeRequest->program->name }}</td>
        <td>
            @if(Auth::user()->admin)
            <form method="POST" action="/requests/{{ $changeRequest->id }}/approve" id="approveRequest{{ $changeRequest->id }}form">
                @csrf
                @if ($changeRequest->session != null)
                {{ $changeRequest->session->start_time->format('l, g:i A') }}
                @else
                <select name="session" required>
                    <option selected disabled hidden>Choose Session</option>
                    @foreach($changeRequest->program->sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->start_time->format('l, g:i A') }}</option>
                    @endforeach
                </select>
                @endif
            </form>
            @else
            Not Selected
            @endif
        </td>
        <td>{{ $changeRequest->action }}</td>
        <td>{{ $changeRequest->notes }}</td>
        <td style="text-align: center;">{!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;">&#10007;</span>' !!}</td>
        @if(Auth::user()->admin)
        <td>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-success" type="submit" form="approveRequest{{ $changeRequest->id }}form">APPROVE</button>
                <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}" id="deleteRequest{{ $changeRequest->id }}form">
                    @method('DELETE')
                    @csrf
                </form>
                <button class="btn btn-sm btn-outline-danger" type="submit" form="deleteRequest{{ $changeRequest->id }}form">DELETE</button>
                <form class="m-0" method="POST" action="/requests/{{ $changeRequest->id }}/waitlist" id="waitlistRequest{{ $changeRequest->id }}form">
                    @csrf
                </form>
                <button class="btn btn-sm btn-outline-warning" type="submit" form="waitlistRequest{{ $changeRequest->id }}form">WAITLIST</button>
            </div>
        </td>
        @else
        <td>Pending</td>
        @endif
    </tr>
    @endforeach
</table>

<h3>Pending Subcamp Confirmation</h3>
<table class="table table-bordered table-sm">
    <thead class="table-dark">
        <tr>
            <th>Created At</th>
            <th>Scout</th>
            <th>Age</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Reqs</th>
            <th>Status</th>
        </tr>
    </thead>
    @foreach ($changeRequests->sortBy('created_at')->where('status', 'approved') as $changeRequest)
    <tr>
        <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
        <td><a href="/scouts/{{ $changeRequest->scout->id}}">{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</a></td>
        <td>{{ $changeRequest->scout->age }}</td>
        <td><a href="/troops/{{$changeRequest->scout->unit}}">{{ $changeRequest->scout->unit }}
                @if ( $changeRequest->scout->subcamp == 'Buckskin')
                (B)
                @elseif ( $changeRequest->scout->subcamp == 'Ten Chiefs')
                (TC)
                @elseif ( $changeRequest->scout->subcamp == 'Voyageur')
                (V)
                @else

                @endif</a></td>
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
        <td style="text-align: center;">{!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;">&#10007;</span>' !!}</td>

        @if(Auth::user()->admin)
        <td>
            <form class="mb-0" method="POST" action="/requests/{{ $changeRequest->id }}/unapprove">
                @csrf
                <button type="submit">Unapprove</button>
            </form>
        </td>
        @elseif(Auth::user()->name == $changeRequest->scout->subcamp)
        <td>
            <form class="mb-0" method="POST" action="/requests/{{ $changeRequest->id }}/confirm">
                @csrf
                <button class="btn btn-sm btn-outline-success" type="submit">Confirm</button>
            </form>
        </td>
        @else
        <td></td>
        @endif
    </tr>
    @endforeach
</table>

<h3>Waitlist</h3>
<table class="table table-bordered table-sm">
    <thead class="table-dark">
        <tr>
            <th>Created At</th>
            <th>Scout</th>
            <th>Age</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Reqs</th>
            <th>Status</th>
        </tr>
    </thead>
    @foreach ($changeRequests->sortBy('created_at')->sortBy('program_id')->where('status', 'waitlist') as $changeRequest)
    <tr>
        <form method="POST" action="/requests/{{ $changeRequest->id }}/approve">
            @csrf
            <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
            <td><a href="/scouts/{{ $changeRequest->scout->id}}">{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</a></td>
            <td>{{ $changeRequest->scout->age }}</td>
            <td><a href="/troops/{{$changeRequest->scout->unit}}">{{ $changeRequest->scout->unit }}
                    @if ( $changeRequest->scout->subcamp == 'Buckskin')
                    (B)
                    @elseif ( $changeRequest->scout->subcamp == 'Ten Chiefs')
                    (TC)
                    @elseif ( $changeRequest->scout->subcamp == 'Voyageur')
                    (V)
                    @else

                    @endif</a></td>
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
            <td style="text-align: center;">{!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;">&#10007;</span>' !!}</td>

            @if(Auth::user()->admin)
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success" type="submit">APPROVE</button>
                    <button class="btn btn-sm btn-outline-danger" type="submit" form="deleteRequest{{ $changeRequest->id }}form">DELETE</button>
                </div>
            </td>
            @else
            <td>Pending</td>
            @endif
        </form>
        <form method="POST" action="/requests/{{ $changeRequest->id }}" id="deleteRequest{{ $changeRequest->id }}form">
            @method('DELETE')
            @csrf
        </form>
    </tr>
    @endforeach
</table>

<h3>Archived Requests</h3>
<table class="table table-bordered table-sm">
    <thead class="table-dark">
        <tr>
            <th>Submitted At</th>
            <th>Confirmed At</th>
            <th>Scout</th>
            <th>Age</th>
            <th>Troop</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Reqs</th>
            <th>Status</th>
        </tr>
    </thead>
    @foreach ($changeRequests->sortBy('changed_at')->where('status', 'archived') as $changeRequest)
    <tr>
        <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
        <td>{{ $changeRequest->updated_at->format('l, g:i A')}}</td>
        <td><a href="/scouts/{{ $changeRequest->scout->id}}">{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</a></td>
        <td>{{ $changeRequest->scout->age }}</td>
        <td><a href="/troops/{{$changeRequest->scout->unit}}">{{ $changeRequest->scout->unit }}
                @if ( $changeRequest->scout->subcamp == 'Buckskin')
                (B)
                @elseif ( $changeRequest->scout->subcamp == 'Ten Chiefs')
                (TC)
                @elseif ( $changeRequest->scout->subcamp == 'Voyageur')
                (V)
                @else

                @endif</a></td>
        <td>{{ $changeRequest->program->name }}</td>
        @if ($changeRequest->session != null)
        <td>{{ $changeRequest->session->start_time->format('l, g:i A') }}</td>
        @else
        <td></td>
        @endif
        <td>{{ $changeRequest->action }}</td>
        <td>{{ $changeRequest->notes }}</td>
        <td style="text-align: center;">{!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;">&#10007;</span>' !!}</td>
        <td>Archived</td>
    </tr>
    @endforeach
    @foreach ($changeRequests->where('status', 'confirmed') as $changeRequest)
    <tr>
        <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
        <td>{{ $changeRequest->created_at->format('l, g:i A')}}</td>
        <td>{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</td>

        <td>{{ $changeRequest->scout->age }}</td>
        <td>{{ $changeRequest->scout->unit }}
            @if ( $changeRequest->scout->subcamp == 'Buckskin')
            (B)
            @elseif ( $changeRequest->scout->subcamp == 'Ten Chiefs')
            (TC)
            @elseif ( $changeRequest->scout->subcamp == 'Voyageur')
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
        <td style="text-align: center;">{!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;">&#10007;</span>' !!}</td>
        <td>Archived</td>
    </tr>
    @endforeach
</table>
@endsection
