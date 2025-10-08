@extends('layouts.app')
@section('title', 'User create')
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/quill.snow.css')}}">
@endpush

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3> editar de Vehiculo</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Vehiculos</li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid">
    <livewire:vehicles.update-vehicle :vehicle_id="$id">
</div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script src="{{asset('assets/js/select2/tagify.js')}}"></script>
    <script src="{{asset('assets/js/select2/tagify.polyfills.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/intltelinput.min.js')}}"></script>

    <script src="{{ asset('assets/js/editors/quill.js') }}"></script>
    <script src="{{asset('assets/js/custom-add-product.js')}}"></script>
    <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
@endpush
