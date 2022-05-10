@extends('layouts.base')
<title>Programs List</title>

@section('content')

    @foreach($programs as $program)
    <h1>
        <a href="/flintlock/programs/{{$program->id}}">{{ $program->name }}</a><br>
    </h1>
    @foreach($program->sessions as $session)
        @if($session->scouts->first() == null)
            @continue
        @else
            <h3>{{ $session->start_time->format('l') }}  </h3>

            <ul>
            @foreach($session->scouts as $scout)
                <li><a href="/flintlock/scouts/{{$scout->id}}">{{ $scout->first_name }} {{ $scout->last_name }} ({{ $scout->gender }}), {{ $scout->site }}, {{ $scout->unit }}</a></li>
            @endforeach
            </ul>
        @endif
    @endforeach
    <div class="pagebreak"> </div>
    @endforeach
@endsection


