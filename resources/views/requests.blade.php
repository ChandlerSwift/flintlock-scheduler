@extends('layouts.base')

@section('content')
<h3>New request</h3>
<form class="row row-cols-lg-auto g-3 align-items-center bg-light rounded" action="/requests" method="POST">
    @csrf
    <div class="col-12">
        <select class="form-select" name="addDrop">
            <option value="" selected disabled hidden>Action</option>
            <option value="Add">Add</option>
            <option value="Drop">Drop</option>
            <option value="Swap">Swap</option>
        </select>
    </div>

    <div class="col-12">
        <select class="form-select" id="unit">
            <option value="test" selected disabled hidden>Choose Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit }}{{ $unit->council }}">{{ $unit->unit }}
                ({{ $scouts->where('unit', $unit->unit)->where('council', $unit->council)->first()->subcampAbbr }})
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <select class="form-select" id="scout" name="Scout" disabled>
            <option value="test" selected disabled hidden>Choose Scout</option>
            @foreach($scouts->sortby('last_name') as $scout)
            <option value="{{ $scout->id }}" data-unit="{{ $scout->unit }}{{ $scout->council }}">{{ $scout->first_name }} {{ $scout->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select class="form-select" id="program" name="program_id">
            <option value="test" selected disabled hidden>Choose Program</option>
            @foreach($programs as $program)
            @if($program->sessions->count() > 0)
            <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endif
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select class="form-select" id="session" name="session">
            <option selected data-enabled="true" value="">Choose Session</option>
            @foreach($sessions as $session)
            <option value="{{ $session->id }}" data-program="{{ $session->program_id }}">{{ $session->start_time->format('l, g:i A') }} ({{ $session->scouts->count() }}/{{ $session->program->max_participants }})</option>
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
    let currentValue = document.getElementById("unit").value;
    if (currentValue == "test") {
        document.getElementById("scout").disabled = true;
    } else {
        document.getElementById("scout").disabled = false;
        document.querySelectorAll("select#scout > option").forEach(function(o) {
            o.hidden = o.dataset['unit'] != currentValue;
        });
    }

    document.getElementById("program").addEventListener("change", function(e) {
        document.getElementById('session').value = "test";
        if (e.target.value !== "test") {
            document.getElementById("session").disabled = false;
            document.querySelectorAll("select#session > option").forEach(function(o) {
                o.hidden = o.dataset['program'] != e.target.value && o.dataset['enabled'] != "true";
            });
        } // else it's the "Choose Unit"
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

    document.getElementById("unit").addEventListener("change", function(e) {
        document.getElementById('scout').value = "test";
        if (e.target.value !== "test") {
            document.getElementById("scout").disabled = false;
            document.querySelectorAll("select#scout > option").forEach(function(o) {
                o.hidden = o.dataset['unit'] != e.target.value;
            });
        }
    });
</script>

<h3 class="mt-5">Pending Flintlock Approval</h3>
@include('components.request-table', ['changeRequests' => $changeRequests->where('status', 'pending')->sortBy('created_at')])

<h3>Pending Subcamp Confirmation</h3>
@include('components.request-table', ['changeRequests' => $changeRequests->where('status', 'approved')->sortBy('created_at')])

<h3>Waitlist</h3>
@include('components.request-table', ['changeRequests' => $changeRequests->where('status', 'waitlist')->sortBy('created_at')->sortBy('program_id')])

<h3>Archived Requests</h3>
@include('components.request-table', ['changeRequests' => $changeRequests->whereIn('status', ['archived', 'confirmed'])->sortBy('changed_at'), 'showConfirmedAt' => true])
@endsection
