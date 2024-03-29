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
    @php
    $session = $program->sessions()->where('week_id', request()->cookie('week_id'))->first();
    @endphp
    @if($session && $session->every_day)
        <table class="table table-sm table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="dark" colspan="5">Monday&ndash;Friday ({{ $session->scouts()->count() }}/{{ $session->program->max_participants }})</th>
                </tr>
            </thead>
            @foreach($session->scouts->sortBy('unit') as $scout)
                <tr>
                    <td><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>@if(!$scout->meetsReqsFor($session->program)) <abbr style="color:red;" title="{{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}">(reqs)</abbr>@endif</td>
                    <td><a href="/units/{{$scout->council}}/{{$scout->unit}}"> {{ $scout->unit }}</a></td>
                    <td>{{ $scout->gender }}</td>
                    <td>{{ $scout->age }}</td>
                    <td>{{ $scout->subcamp }}</td>
                </tr>
            @endforeach
        </table>
    @else
        @foreach($program->sessions->where('week_id', request()->cookie('week_id'))->sortBy('start_time') as $session)
        <div class="col">
            <table class="table table-sm table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="dark" colspan="5">
                            @if($session->start_time->format('i') == "00")
                                {{ $session->start_time->format('l gA') }}
                            @else
                                {{ $session->start_time->format('l g:i A') }}
                            @endif
                            ({{ $session->scouts->count() }}/{{ $session->program->max_participants }})
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($session->scouts->sortBy('unit') as $scout)
                    <tr>
                        <td><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }}</a>@if(!$scout->meetsReqsFor($session->program)) <abbr style="color:red;" title="{{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}">(reqs)</abbr>@endif</td>
                        <td><a href="/units/{{$scout->council}}/{{$scout->unit}}"> {{ $scout->unit }}</a></td>
                        <td>{{ $scout->gender }}</td>
                        <td>{{ $scout->age }}</td>
                        <td>{{ $scout->subcamp }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
    @endif
</div>
@endforeach
@endsection
