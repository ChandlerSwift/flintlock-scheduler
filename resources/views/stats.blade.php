@extends('layouts.base')
<title>Admin</title>
@section('content')
@foreach($subcamps as $subcamp => $preferences)
{{$subcamp}}: &nbsp; {{ $preferences->where('satisfied', true)->count() }}/{{$preferences->count()}} &nbsp;&nbsp;
{{round(( $preferences->where('satisfied', true)->count() / $preferences->count() ) * 100 )}} %
<br>

@endforeach
@endsection