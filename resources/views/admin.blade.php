@extends('layouts.base')
<title>Admin</title>
@section('content')
<div class="list">
    <a class="button" href="/admin/import_data" onclick="return confirm('Are you sure you want import new data? This will seed the database.');" >Import Data</a><br>
    <a class="button" href="/admin/plan_week" onclick="return confirm('Are you sure you want to schedule?');" >Plan Week</a><br>
    <a class="button" href="/admin/stats"  >Stats</a><br>
    <a class="button" href="/admin">Import Other Data</a><br>

</div>
@endsection 