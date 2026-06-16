@extends('layouts.app')
<div class="container mt-5">
    @section('title')
        Listado Control Airbnb
    @endsection
    <br><br><br>
    <div class="row mb-1">
        <div class="col-1">
            <a href="javascript:history.back()" class="text-silver"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
        </div>
        <div class="col-10">
            <h4 class="text-secondary text-center">Listado Control Airbnb</h4>
        </div>
        <div class="col-1"></div>
    </div>
    @livewire('ctrlairbnb')
</div>