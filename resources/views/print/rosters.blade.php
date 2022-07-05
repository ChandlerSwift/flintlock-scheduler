@extends('layouts.base')
<title>Rosters</title>
@section('content')
<style>
    @media print {
        div.nobreak {
            break-inside: avoid;
        }
    }
</style>
@foreach($sessions as $session)
<div class="nobreak">
    @if($session->scouts->first() == null)
        @continue
    @else
        <h3>{{ $session->program->name }} {{ $session->start_time->format('l') }}</h3>

        <ul>
        @foreach($session->scouts as $scout)
            <li><a href="/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}, {{ $scout->age }}), {{ $scout->site }}, {{ $scout->unit }}@if(!$scout->meetsReqsFor($session->program)) (needs {{ implode(', ', $scout->missingReqsFor($session->program)->pluck('name')->all()) }}) @endif</a></li>
        @endforeach
        </ul>
    @endif
</div>
@endforeach
@endsection 
