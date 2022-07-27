@extends('layouts.base')
@section('content')
@foreach($sessions as $session)
<div class="nobreak">
    @if($session->scouts->first() == null)
        @continue
    @else
        <h3>{{ $session->program->name }} {{ $session->start_time->format('l') }}</h3>

        <ul>
        @foreach($session->scouts as $scout)
            <li style="list-style-type: '\2610'; padding-left: 0.5em;">
                <a href="/scouts/{{$scout->id}}">
                    {{ $scout->first_name }} {{ $scout->last_name }}
                    ({{ $scout->gender }}, {{ $scout->age }}),
                    {{ $scout->site }} ({{ $scout->subcampAbbr }}), {{ $scout->unit }}
                    @if(!$scout->meetsReqsFor($session->program))
                        (needs {{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }})
                    @endif
                </a>
            </li>
        @endforeach
        @foreach($session->changeRequests()->where('status', 'approved')->get()->pluck('scout') as $scout)
            <li style="list-style-type: '\2610'; padding-left: 0.5em;">
                <a href="/scouts/{{$scout->id}}">
                    {{ $scout->first_name }} {{ $scout->last_name }}
                    ({{ $scout->gender }}, {{ $scout->age }}),
                    {{ $scout->site }} ({{ $scout->subcampAbbr }}), {{ $scout->unit }}
                    @if(!$scout->meetsReqsFor($session->program))
                        (needs {{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }})
                    @endif
                    (pending subcamp confirmation)
                </a>
            </li>
        @endforeach
        </ul>
    @endif
</div>
@endforeach
@endsection 
