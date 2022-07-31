@extends('layouts.base')
@section('content')
<h2>Assign troops to sites</h2>
<p>
    These can be pulled from scoutingevent.com:
    <ul>
        <li><a href="https://scoutingevent.com/250-Buckskin2022">https://scoutingevent.com/250-Buckskin2022</a></li>
        <li><a href="https://scoutingevent.com/250-TenChiefs2022">https://scoutingevent.com/250-TenChiefs2022</a></li>
        <li><a href="https://scoutingevent.com/250-Voyageur2022">https://scoutingevent.com/250-Voyageur2022</a></li>
    </ul>
</p>
@foreach($weeks_missing_sites as $week)
<h3>{{ $week['week']->name }}</h3>
<form class="row g-3 align-items-center mt-2" action="/admin/assign_sites" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="week" value="{{ $week['week']->id }}">
    @foreach($week['subcamps'] as $subcamp)
    <h4>{{ $subcamp['subcamp'] }}</h4>
        @foreach($subcamp['units'] as $unit)
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">{{ $unit['unit'] }} ({{ $unit['council'] }})</label>
            <div class="col-sm-10">
                <input class="form-control" list="site-list" placeholder="Type to search..." name="{{ $unit['unit'] }}-{{ $unit['council'] }}" required>
                <datalist id="site-list">
                    <option selected disabled hidden>Choose a Subcamp/Site</option>
                    <option value="Beard">Buckskin: Beard</option>
                    <option value="Boone">Buckskin: Boone</option>
                    <option value="Bridger">Buckskin: Bridger</option>
                    <option value="Carson">Buckskin: Carson</option>
                    <option value="Cody">Buckskin: Cody</option>
                    <option value="Crockett">Buckskin: Crockett</option>
                    <option value="Dixon">Buckskin: Dixon</option>
                    <option value="Fitzpatrick">Buckskin: Fitzpatrick</option>
                    <option value="Hartman">Buckskin: Hartman</option>
                    <option value="Maxwell">Buckskin: Maxwell</option>
                    <option value="Rogers">Buckskin: Rogers</option>
                    <option value="Rolette">Buckskin: Rolette</option>
                    <option value="Seton">Buckskin: Seton</option>
                    <option value="Tyler">Buckskin: Tyler</option>
                    <option value="All Star">All Star: All Star</option>
                    <option value="Black Hawk">Ten Chiefs: Black Hawk</option>
                    <option value="Cochise">Ten Chiefs: Cochise</option>
                    <option value="Joseph">Ten Chiefs: Joseph</option>
                    <option value="Massasoit">Ten Chiefs: Massasoit</option>
                    <option value="Pontiac">Ten Chiefs: Pontiac</option>
                    <option value="Powhatan">Ten Chiefs: Powhatan</option>
                    <option value="Red Cloud">Ten Chiefs: Red Cloud</option>
                    <option value="Roman Nose">Ten Chiefs: Roman Nose</option>
                    <option value="Samoset">Ten Chiefs: Samoset</option>
                    <option value="Sequoyah">Ten Chiefs: Sequoyah</option>
                    <option value="Skenandoa">Ten Chiefs: Skenandoa</option>
                    <option value="Tamanend">Ten Chiefs: Tamanend</option>
                    <option value="Taskalusa">Ten Chiefs: Taskalusa</option>
                    <option value="Tecumseh">Ten Chiefs: Tecumseh</option>
                    <option value="Chisholm">Voyageur: Chisholm</option>
                    <option value="Duluth">Voyageur: Duluth</option>
                    <option value="Ely">Voyageur: Ely</option>
                    <option value="Fon Du Lac">Voyageur: Fon Du Lac</option>
                    <option value="Ft. Francis">Voyageur: Ft. Francis</option>
                    <option value="Ft. William">Voyageur: Ft. William</option>
                    <option value="Grand Marais">Voyageur: Grand Marais</option>
                    <option value="Grand Portage">Voyageur: Grand Portage</option>
                    <option value="Hibbing">Voyageur: Hibbing</option>
                    <option value="Quetico">Voyageur: Quetico</option>
                    <option value="Two Harbors">Voyageur: Two Harbors</option>
                    <option value="Vermilion">Voyageur: Vermilion</option>
                </datalist>
            </div>
        </div>
        @endforeach
    @endforeach
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update week</button>
    </div>
</form>
@endforeach
@endsection
