@extends('layouts.base')
@section('content')
<form method="POST">
    @csrf
    <style>div.form-input { margin-bottom: 1em; } div.form-input label { margin-right: 1em; }</style>
    <div class="form-input">
        <label for="first_name">First name</label><input name="first_name" type="text">
    </div>
    <div class="form-input">
        <label for="last_name">Last name</label><input name="last_name" type="text">
    </div>
    <div class="form-input">
        <label for="gender">Gender</label><input name="gender" type="text">
    </div>
    <div class="form-input">
        <label for="unit">Unit</label><input name="unit" type="text">
    </div>
    <div class="form-input">
        <label for="site">Site</label><input name="site" type="text">
    </div>
    <div class="form-input">
        <label for="subcamp">Subcamp</label>
        <select name="subcamp">
            <option value="Buckskin">Buckskin</option>
            <option value="Ten Chiefs">Ten Chiefs</option>
            <option value="Voyageur">Voyageur</option>
        </select>
    </div>
    <div class="form-input">
        <label for="rank">Rank</label>
        <select name="rank">
            <option value="0">Scout</option>
            <option value="1">Tenderfoot</option>
            <option value="2">Second Class</option>
            <option value="3">First Class</option>
            <option value="4">Star</option>
            <option value="5">Life</option>
            <option value="6">Eagle</option>
        </select>
    </div>
    <div class="form-input">
        <label for="age">Age</label>
        <input type="number" min="10" max="21">
    </div>
    <div class="form-input">
        <input type="submit">
    </div>
</form>
@endsection
