@extends('layouts.app')
@section('title', 'User create')
@push('css')

@endpush

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3> Lista de Vehiculos</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Vehiculos</li>
                    <li class="breadcrumb-item active">Listado</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid list-product-view product-wrapper">
    <livewire:vehicles.list-vehicles>

</div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
@endpush
