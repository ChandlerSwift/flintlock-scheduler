<table class="table table-bordered table-sm">
    <thead class="table-dark">
        <tr>
            <th>Created At</th>
            @if($showConfirmedAt ?? false)
            <th>Confirmed At</th>
            @endif
            <th>Scout</th>
            <th>Age</th>
            <th>Unit</th>
            <th>Program</th>
            <th>Session</th>
            <th>Action</th>
            <th>Notes</th>
            <th>Reqs</th>
            <th>Status</th>
        </tr>
    </thead>
    @foreach ($changeRequests as $changeRequest)
    <tr>
        <td>{{ $changeRequest->created_at->format('l, g:i A') }}</td>
        @if($showConfirmedAt ?? false)
        <td>{{ $changeRequest->updated_at->format('l, g:i A') }}</td>
        @endif
        <td><a href="/scouts/{{ $changeRequest->scout->id}}">{{ $changeRequest->scout->first_name }} {{ $changeRequest->scout->last_name }}</a></td>
        <td>{{ $changeRequest->scout->age }}</td>
        <td><a href="/units/{{$changeRequest->scout->council}}/{{$changeRequest->scout->unit}}">{{ $changeRequest->scout->unit }} ({{ $changeRequest->scout->subcampAbbr }})</a></td>
        <td>{{ $changeRequest->program->name }}</td>
        <td>
            @if($changeRequest->session != null)
            {{ $changeRequest->session->start_time->format('l, g:i A') }}
            @elseif(Auth::user()->admin)
            <select name="session" required form="approveRequest{{ $changeRequest->id }}form">
                <option selected disabled hidden>Choose Session</option>
                @foreach($changeRequest->program->sessions()->where('week_id', request()->cookie('week_id'))->get()  as $session)
                <option value="{{ $session->id }}">{{ $session->start_time->format('l, g:i A') }}</option>
                @endforeach
            </select>
            @else
            Not Selected
            @endif
        </td>
        <td>{{ $changeRequest->action }}</td>
        <td>{{ $changeRequest->notes }}</td>
        <td style="text-align: center;">
            @if($changeRequest->action == "Add")
            {!! $changeRequest->scout->meetsReqsFor($changeRequest->program) ? '<span style="color:green;">&check;</span>' : '<span style="color:red;"><abbr title="missing ' . implode(', ', $changeRequest->scout->missingReqsFor($changeRequest->program)->pluck('name')->all()) . '">&#10007;</abbr></span>' !!}
            @else
            &ndash;
            @endif
        </td>
        <td>
            <div class="btn-group">
                @if(Auth::user()->admin && ($changeRequest->status == "pending" || $changeRequest->status == "waitlist"))
                <button class="btn btn-sm btn-outline-success" type="submit" form="approveRequest{{ $changeRequest->id }}form">Approve</button>
                <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}/approve" id="approveRequest{{ $changeRequest->id }}form">
                    @csrf
                </form>
                <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}" id="deleteRequest{{ $changeRequest->id }}form">
                    @method('DELETE')
                    @csrf
                </form>
                <button class="btn btn-sm btn-outline-danger" type="submit" form="deleteRequest{{ $changeRequest->id }}form">Delete</button>
                @endif
                @if(Auth::user()->admin && $changeRequest->status == "pending")
                <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}/waitlist" id="waitlistRequest{{ $changeRequest->id }}form">
                    @csrf
                </form>
                <button class="btn btn-sm btn-outline-warning" type="submit" form="waitlistRequest{{ $changeRequest->id }}form">Waitlist</button>
                @endif
                @if($changeRequest->status == "approved")
                    @if(Auth::user()->admin)
                    <button class="btn btn-sm btn-outline-danger" type="submit" form="unapproveRequest{{ $changeRequest->id }}form">Unapprove</button>
                    <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}/unapprove" id="unapproveRequest{{ $changeRequest->id }}form">
                        @csrf
                    </form>
                    @elseif(Auth::user()->name == $changeRequest->scout->subcamp)
                    <button class="btn btn-sm btn-outline-success" type="submit" form="confirmRequest{{ $changeRequest->id }}form">Confirm</button>
                    <form class="d-none" method="POST" action="/requests/{{ $changeRequest->id }}/confirm" id="confirmRequest{{ $changeRequest->id }}form">
                        @csrf
                    </form>
                    @endif
                @endif
            </div>
        </td>
    </tr>
    @endforeach
</table>
