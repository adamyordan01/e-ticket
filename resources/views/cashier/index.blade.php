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
        <div class="breadcrumb-item">Kasir</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h3>Produk</h3>
                </div>
                <div class="card-body">
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
                                            <h2 style="font-size: 13pt; color: #5a5b5b">
                                                {{ $product->name }}
                                            </h2>
                                        </div>
                                        <p class="text-primary" style="font-size: 18pt; font-weight:600">
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
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" id="dataTotal">
            <div class="card shadow-none">
                <div class="card-header">
                    <h3>Pesanan</h3>
                </div>
                <div class="card-body">
                    @php
                        $subtotal = 0;
                        $tax = 0;
                        $total = 0;
                    @endphp
                    @foreach ($tempTransactions as $tempTransaction)
                        <div class="card shadow mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 pl-0">
                                        <img src="{{ asset('product') . "/" .$tempTransaction->product->image }}" class="product-image" alt="{{ $tempTransaction->product->name }}">
                                    </div>
                                    <div class="col-md-5 pl-4">
                                        <p class="text-primary mb-0" style="font-size: 15pt; font-weight:600">
                                            {{ $tempTransaction->product->name }}
                                        </p>
                                        <p>
                                            Rp.{{ number_format($tempTransaction->product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 px-0">
                                        <form class="form-inline">
                                            <a href="javascript:void(0)" onclick="funcMin('{{ $loop->iteration }}', '{{ $tempTransaction->product_id }}')" class="btn btn-primary btn-sm dec-qty" id="dec-qty" data-id="{{ $tempTransaction->product_id }}">
                                                <i class="fas fa-minus"></i>
                                            </a>
                                            <input type="text" style="width: 40%; text-align:center" class="form-control mx-1" id="quantity{{ $loop->iteration }}" value="{{ $tempTransaction->quantity }}">
                                            <a href="javascript:void(0)" onclick="funcPlus('{{ $loop->iteration }}', '{{ $tempTransaction->product_id }}')" class="btn btn-primary btn-sm inc-qty" id="inc-qty" data-id="{{ $tempTransaction->product_id }}">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </form>
                                    </div>
                                    <div class="col-md-1 ml-auto">
                                        <a href="javascript:void(0)" class="btn-link text-danger" data-id="{{ $tempTransaction->product_id }}" id="delete-cart">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $subtotal += $tempTransaction->product->price * $tempTransaction->quantity;
                            $tax += $tempTransaction->product->price * $tempTransaction->quantity * 0.11;
                            $total = $subtotal + $tax;
                        @endphp
                    @endforeach
                    <input type="hidden" id="total" value="{{ $total }}" class="d-none">
                    <input type="hidden" id="total_return2" name="total_return2" class="d-none">
                    <input type="hidden" id="total_tax" name="total_tax" class="d-none" value="{{ $tax }}">
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
                                <p style="font-size: 13pt; font-weight:500">Pajak (11%)</p>
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
@endsection

@push('script')
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
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
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Transaksi berhasil diproses',
                            timer: 3000,
                            icon: 'success',
                            showConfirmButton: false
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