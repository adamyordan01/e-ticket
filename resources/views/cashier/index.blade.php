@extends('layouts.base', ["title" => "Kasir"])

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
            width: 55px;
            height: 55px;
            -webkit-border-radius: 60px;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 60px;
            -moz-background-clip: padding;
            border-radius: 60px;
            background-clip: padding-box;
            margin: 0px 12px 0 0px;
            float: left;
            background-size: cover;
            background-position: center center;
        }

        .product-table {
            height: 40vh;
            overflow-y: auto;
        }

        .list-ticket {
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px;
        }

        ul {
            list-style: none;
        }

        ul.product-list {
            padding: 0;
        }

        .product-list li {
            padding: 15px;
            margin: 0;
            border-bottom: 1px solid #e6e6e6;
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin: 0 0 15px;
            display: flex;
            align-items: center;
        }

        .product-images .product-img {
            display: flex;
            margin-right: 10px;
        }

        .product-images .product-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        /* width */
        ::-webkit-scrollbar {
        width: 6px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #555;
        }
    </style>
@endpush

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Kasir</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h3>Produk</h3>
                </div>
                <div class="card-body">
                    <div class="list-ticket">
                        <div class="row">
                            @foreach ($products as $product)
                                <input type="hidden" name="product_id" class="d-none" id="product_id" value="{{ $product->id }}">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <article class="article article-style-b">
                                        <div class="article-header">
                                            <div class="article-image" data-background="{{ asset('product/' . $product->image) }}">
                                            </div>
                                        </div>
                                        <div class="article-details">
                                            <div class="article-title">
                                                <h2 style="font-size: 10pt; color: #5a5b5b">
                                                    {{ $product->name }}
                                                </h2>
                                            </div>
                                            <p class="text-primary" style="font-size: 13pt; font-weight:600">
                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                            <div class="article-cta">
                                                <a class="btn btn-primary btn-lg btn-block" id="add-to-cart" data-id="{{ $product->id }}" href="javascript:void(0)">
                                                    <i class="fas fa-cart-plus"></i>
                                                    Pesan Tiket
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" id="dataTotal">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3>Pesanan</h3>
                </div>
                <div class="card-body product-table">
                    @php
                        $subtotal = 0;
                        $tax = 0;
                        $total = 0;
                    @endphp
                    @foreach ($tempTransactions as $tempTransaction)
                        <div class="card shadow mb-2">
                            <div class="card-body p-2">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <img class="product-image" src="{{ asset('product/' . $tempTransaction->product->image) }}" alt="">
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 ml-2">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 pr-0">
                                                <p class="mb-0" style="font-size: 10pt; font-weight: 600">
                                                    {{ $tempTransaction->product->name }}
                                                </p>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 pr-0">
                                                <p class="mb-1" style="font-size: 10pt; font-weight: 600">
                                                    {{-- {{ $tempTransaction->product->price }} --}}
                                                    Rp{{ number_format($tempTransaction->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="col-l-10g col-md-10 col-sm-10 pr-0">
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <a href="javascript:void(0)" onclick="funcMin('{{ $loop->iteration }}', '{{ $tempTransaction->product_id }}')" id="dec-qty"id="dec-qty" data-id="{{ $tempTransaction->product_id }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-minus"></i>
                                                        </a>
                                                    </div>
                                                    <input style="width: 30% !important; text-align:center" type="text" onkeyup="input('{{ $loop->iteration }}', '{{ $tempTransaction->product_id }}')" class="form-control form-control-sm" id="quantity{{ $loop->iteration }}" value="{{ $tempTransaction->quantity }}">
                                                    <div class="input-group-append">
                                                        <a href="javascript:void(0)" onclick="funcPlus('{{ $loop->iteration }}', '{{ $tempTransaction->product_id }}')" id="inc-qty"id="inc-qty" data-id="{{ $tempTransaction->product_id }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 ml-1">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                <p class="text-dark mb-0" style="font-size: 8pt">
                                                    Pajak : {{ $tempTransaction->product->tax }}%
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                                <p style="font-size: 9pt">
                                                    Rp.{{ number_format($tempTransaction->product->price * ($tempTransaction->product->tax / 100) * $tempTransaction->quantity, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <a href="javascript:void(0)" data-id="{{ $tempTransaction->product_id }}" id="delete-cart">
                                            <i class="far fa-trash-alt text-danger"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $subtotal += $tempTransaction->product->price * $tempTransaction->quantity;
                            // $tax += $tempTransaction->product->price * $tempTransaction->quantity * 0.11;
                            $tax += $tempTransaction->product->price * $tempTransaction->quantity * $tempTransaction->product->tax / 100;
                            // $total = $subtotal + $tax;
                            $total = $subtotal + $tax;
                        @endphp
                    @endforeach
                    <input type="hidden" id="total" value="{{ $total }}" class="d-none">
                    <input type="hidden" id="total_return2" name="total_return2" class="d-none">
                    <input type="hidden" id="total_tax" name="total_tax" class="d-none" value="{{ $tax }}">
                    {{-- <div class="mt-5">
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:500">Subtotal</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:600">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:500">Total Pajak</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:600">Rp{{ number_format($tax, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <hr style="border-top:dashed 2px">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:600">Total</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:700">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="total_paid" style="font-size: 12pt">Bayar</label>
                                    <input type="text" class="form-control total_paid" name="total_paid" id="total_paid">
                                </div>
                                <div class="form-group mt-0">
                                    <label for="total_return" style="font-size: 12pt">Kembalian</label>
                                    <input type="text" class="form-control" name="total_return" id="total_return">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button class="btn btn-primary btn-lg btn-block" id="payment" style="font-size: 14pt">Bayar</button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="mt-5">
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:500">Subtotal</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:600">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:500">Total Pajak</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:600">Rp{{ number_format($tax, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <hr style="border-top:dashed 2px">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <p style="font-size: 13pt; font-weight:600">Total</p>
                            </div>
                            <div class="col-auto">
                                <p style="font-size: 14pt; font-weight:700">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label for="total_paid" style="font-size: 12pt">Bayar</label>
                                    <input type="text" class="form-control total_paid" name="total_paid" id="total_paid">
                                </div>
                                <div class="form-group mt-0">
                                    <label for="total_return" style="font-size: 12pt">Kembalian</label>
                                    <input type="text" class="form-control" name="total_return" id="total_return">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button class="btn btn-primary btn-lg btn-block" id="payment" style="font-size: 14pt">Bayar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- tabel daftar transaksi per kasir --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 17pt" class="card-title">Daftar Transaksi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Subtotal</th>
                                    <th>Pajak</th>
                                    <th>Grand Total</th>
                                    <th>Bayar</th>
                                    <th>Kembalian</th>
                                    <th style="width: 15%">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/datatable/datatables.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction.get-transaction') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'transaction_date', name: 'transaction_date'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'tax', name: 'tax'},
                    {data: 'grand_total', name: 'grand_total'},
                    {data: 'total_paid', name: 'total_paid'},
                    {data: 'total_return', name: 'total_return'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            $(document).on('click', '#add-to-cart', function(e) {
                let product_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('temp-transaction.store') }}",
                    method: "POST",
                    data: {
                        product_id: product_id,
                    },
                    dataType: "json",
                    success: function(data) {
                    }
                })
                getTotal()
                getTotal()
            })
            
            $(document).on('click', '#delete-cart', function(e) {
                let product_id = $(this).data('id');
                console.log(product_id);
                $.ajax({
                    // url: "{{ route('temp-transaction.destroy') }}",
                    url: "{{ route('temp-transaction.destroy') }}",
                    method: "POST",
                    data: {
                        product_id: product_id,
                    },
                    dataType: "json",
                    success: function(data) {
                    }
                })
                getTotal()
                getTotal()
            })

            $('body').on('change', '#total_paid', function () {
                let total = $('#total').val();
                let paid = $('#total_paid').val();
                // replace . in paid with ''
                paid = paid.replace(/\./g, '');
                paid = parseInt(paid);
                total = parseInt(total);

                let total_return = paid - total;
                let total_return2 = total_return;

                let result = Intl.NumberFormat('id-ID').format(paid);
                total_return = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(total_return);

                $('#total_return').val(total_return);
                $('#total_return2').val(total_return2);
                $('#total_paid').val(result);

            })

            $('#total_paid').on('keyup', function () {
                let total_paid = $('#total_paid').val();
                if (total_paid == '') {
                    $('#total_paid').val('');
                    $('#total_return').val('');
                    $('#total_return2').val('');
                } else {
                    let total = $('#total').val();
                    // total_paid = total_paid.replace('.', '');
                    total_paid = total_paid.replace(/\./g, '');
                    total_paid = parseInt(total_paid);
                    total = parseInt(total);
                    let total_return = total_paid - total;
                    let total_return2 = total_return;
                    let result = new Intl.NumberFormat('id-ID').format(total_paid);
                    total_return = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(total_return);
                    $('#total_return').val(total_return);
                    $('#total_return2').val(total_return2);
                    $('#total_paid').val(result);
                }
            });            
        })

        function getTotal() {
            $.ajax({
                url: "{{ route('temp-transaction.create') }}",
                method: "GET",
                dataType: "html",
                success: function(data) {
                    $('#dataTotal').html(data);
                }
            })
        }

        function input (row, product) {
            let qty = $('#quantity' + row).val();
            console.log(qty);
            if (qty!= ''){
                if (qty <= 0) {
                    $.ajax({
                        url: "{{ route('temp-transaction.destroy') }}",
                        method: "POST",
                        dataType: "json",
                        data: {
                            product_id: product,
                        },
                        success: function(data) {
                        }
                    })
                    getTotal();
                    getTotal();
                } else {
                    $.ajax({
                        url: "{{ route('temp-transaction.index') }}" + '/' + product,
                        method: "PUT",
                        dataType: "json",
                        data: {
                            status: 'input',
                            qty: qty,
                        },
                        success: function (data) {
                            // $('#quantity' + row).val(data.quantity);
                        }
                    })
                    getTotal();
                    getTotal();
                }
            }

            
        }

        function funcPlus(row, product){
            let qty = $('#quantity' + row).val();
            $.ajax({
                url: "{{ route('temp-transaction.index') }}" + '/' + product,
                method: "PUT",
                dataType: "json",
                data: {
                    qty: qty,
                    status: 'plus'
                },
                success: function(data) {
                    
                }
            })
            getTotal();
            getTotal();
        }

        function funcMin(row, product){
            let qty = $('#quantity' + row).val();
            if (qty == 1){
                $.ajax({
                    url: "{{ route('temp-transaction.destroy') }}",
                    method: "POST",
                    dataType: "json",
                    data: {
                        product_id: product,
                    },
                    success: function(data) {
                    }
                })
                getTotal();
                getTotal();
            } else {
                $.ajax({
                    url: "{{ route('temp-transaction.index') }}" + '/' + product,
                    method: "PUT",
                    dataType: "json",
                    data: {
                        qty: qty,
                        status: 'min'
                    },
                    success: function(data) {
                        
                    }
                })
            }
            
            getTotal();
            getTotal();
        
        }

        $('#payment').on('click', function (e) {
            e.preventDefault();
            let total = $('#total').val();
            let total_paid = $('#total_paid').val();

            total_paid = total_paid.replace(/\./g, '');
            total_paid = parseInt(total_paid);
            total = parseInt(total);
            // console.log(total_paid);
            // if total_paid is nan
            if (isNaN(total_paid)){
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Total bayar tidak boleh kosong',
                    timer: 2000,
                    icon: 'warning',
                    showConfirmButton: false
                })
            } else if (total_paid < total){
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Jumlah pembayaran yang anda masukkan kurang',
                    timer: 2000,
                    icon: 'warning',
                    showConfirmButton: false
                })
            } else {
                let total_return = total_paid - total;
                $('#total_return').val(total_return);
                let tax = $('#total_tax').val();
                // console.log(total_return);
                $.ajax({
                    url: "{{ route('cashier.store') }}",
                    method: "POST",
                    data: {
                        total: total,
                        total_paid: total_paid,
                        total_return: total_return,
                        tax: tax,
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $('#data-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Transaksi berhasil diproses',
                            timer: 3000,
                            icon: 'success',
                            showConfirmButton: false
                        }).then(function () {
                            // redirect to print page
                            // window.location.href = "{{ route('print.index') }}" + "/" + data.id, '_blank';
                            window.open("{{ route('print.index') }}" + "/" + data, '_blank');
                        })
                        getTotal()
                        print(data);
                        
                    }
                        
                })
            }
        })

        function print(id){
            $.ajax({
                url: "{{ route('print.index') }}" + '/' + id,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                }
            })
        }       
    </script>
@endpush