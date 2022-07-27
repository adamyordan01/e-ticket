@extends('layouts.base', ["title" => "Dashboard"])

@push('style')
    <style>
        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">Dashboard</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    Selamat datang, {{ auth()->user()->name }}
                </div>
            </div>
        </div>
    </div>
@endsection