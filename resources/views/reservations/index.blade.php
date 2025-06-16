@extends('layouts.app')
@section('title', 'Vehiculos disponibles')
@push('css')

@endpush
@section('main_content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Vehiculos disponibles</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Ecommerce</li>
                    <li class="breadcrumb-item active">Product Grid</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid product-wrapper">
    <livewire:vehicle.available />
</div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
<script  src="{{  asset('assets/js/flat-pickr/flatpickr.js')}}"  ></script>
@endpush
