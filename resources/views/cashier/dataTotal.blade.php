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
<script>
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
                timer: 3000,
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
                        timer: 2000,
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
