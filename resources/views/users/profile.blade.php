@extends('layouts.app')
@section('title', 'Perfil del usuario')
@push('css')

@endpush

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Usuario: {{ $user->name }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg></a></li>
                    <li class="breadcrumb-item">Usuario</li>
                    <li class="breadcrumb-item active">Perfil del usuario: {{ $user->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- Container-fluid starts-->
<div class="container-fluid">
    <livewire:users.profile :$user/>
</div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
@endpush
