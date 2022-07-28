@extends('layouts.base', ["title" => "Check Ticket"])

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">
    <style>
        /* table, th, td {
            border: 1px solid #ced3d9 !important;
            border-collapse: collapse !important;
        } */
        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }

        th.first {
            min-width: 95px !important;
        }

        table {
            width: 100% !important;
        }

        .product-image{
            width: 75px;
            height: 75px;
            -webkit-border-radius: 60px;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 60px;
            -moz-background-clip: padding;
            border-radius: 60px;
            background-clip: padding-box;
            margin: 7px 0 0 5px;
            float: left;
            background-size: cover;
            background-position: center center;
        }
    </style>
@endpush

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Periksa Tiket</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3>Periksa Tiket</h3>
                </div>
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="form-group">
                            <label for="barcode">No. Barcode</label>
                            <input type="text" id="barcode" class="form-control" autofocus>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5" id="dataTotal">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3>Status</h3>
                </div>
                <div class="card-body" id="bodyStatus">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#barcode').focus();
        $('#barcode').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                cekstatus()
            }
        });

        function cekstatus(){
            var barcode = $('#barcode').val();
            $.ajax({
                url: "{{ route('check.index') }}" + '/' + barcode,
                type: "GET",
                success: function(response){
                    $('#bodyStatus').html(response);
                }
            });
            $('#barcode').val('');
            $('#barcode').focus();
        }
    </script>

@endpush