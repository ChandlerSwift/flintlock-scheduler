@extends('layouts.base')
@section('content')
<style>
    .container a:not(:hover) {
        text-decoration: none;
        color: var(--bs-body-color);
    }
    .row {
        --bs-gutter-x:0.5em;
    }
</style>
@foreach($programs as $program)
<h3>{{ $program->name }}</h3>
<div class="row">
    @foreach($program->sessions->sortBy('start_time') as $session)
    <div class="col">
        @if($session->running)
        <table class="table table-sm table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="dark" colspan="5">{{ $session->start_time->format('l') }} ({{ $session->scouts->count() }}/{{ $session->program->max_participants }})</th>
                </tr>
            </thead>
            @foreach($session->scouts->sortBy('troop') as $scout)
                <tr>
                    <td><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>@if(!$scout->meetsReqsFor($session->program)) <abbr style="color:red;" title="{{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}">(reqs)</abbr>@endif</td>
                    <td><a href="/troops/{{$scout->unit}}"> {{ $scout->unit }}</a></td>
                    <td>{{ $scout->gender }}</td>
                    <td>{{ $scout->age }}</td>
                    <td>{{ $scout->subcamp }}</td>
                </tr>
            @endforeach
            </table>
        @else
        <table class="table table-sm table-dark program">
            <tr>
                <th>No Session Scheduled</th>
            </tr>
        </table>
        @endif
    </div>
        @if($loop->last){{-- add empty cells for remaining days, if any --}}
            @for($i = $loop->iteration; $i < 5; $i++)
            <div class="col">
                <table class="table table-sm table-dark program">
                    <tr>
                        <th>No Session Scheduled</th>
                    </tr>
                </table>
            </div>
            @endfor
        @endif
    @endforeach
</div>
@endforeach
@endsection
