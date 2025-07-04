@extends('layouts.app')
@section('title', 'Users')
@push('css')

@endpush

@section('main_content')
 <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>User List</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"> <svg class="stroke-icon">
                                                <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Users</li>
                                    <li class="breadcrumb-item active">User List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div><!-- Container-fluid starts-->
                <div class="container-fluid user-list-wrapper">
                    <div class="row">
                        <livewire:users.use-list/>

                    </div>
                </div><!-- Container-fluid Ends-->
@endsection

@push('scripts')
@endpush
