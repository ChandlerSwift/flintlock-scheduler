@extends('layouts.base')
<title>Admin</title>
@section('content')
<a style="color:blue;" href="/admin/import_data" onclick="return confirm('Are you sure you want import new data? This will seed the database.');" >Import Data</a><br>
<a style="color:blue;" href="/admin/plan_week" onclick="return confirm('Are you sure you want to schedule?');" >Plan Week</a><br>
<a style="color:blue;" href="/admin/stats"  >Stats</a><br>
<a style="color:blue;" href="/admin">Import Other Data</a><br>
@endsection