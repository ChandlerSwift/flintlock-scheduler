@extends('layouts.base')

@section('title')
Admin: Import Data
@endsection

@section('content')
<h2 class="mb-5">Import Data</h2>
<form action="/admin/import_data" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <select class="form-select" name="week_id">
            @foreach($weeks as $week)
                <option value="{{ $week->id }}">{{ $week->name }} (starts {{ $week->start_date->format('l, Y-m-d') }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <input class="form-control" type="file" name="spreadsheet" required>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Import</button>
    </div>
</form>
@endsection
