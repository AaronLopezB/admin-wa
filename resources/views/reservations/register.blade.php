@extends('layouts.app')
@section('title', 'Registrar Orden')
@push('css')
<script src="https://js.stripe.com/v3/"></script>
@endpush
@section('main_content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Registrar</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Reservas</li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid">
    <livewire:reservations.order />
</div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
{{-- <script  src="{{  asset('assets/js/form-wizard/custom-number-wizard.js')}}"  ></script> --}}
{{-- <script  src="{{  asset('assets/js/form-validation-custom.js')}}"  ></script> --}}

<script  src="{{  asset('assets/js/cleave/cleave.min.js')}}"  ></script>

@endpush
