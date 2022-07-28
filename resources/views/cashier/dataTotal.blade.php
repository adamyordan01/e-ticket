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
                            <a href="javascript:void(0)" class="btn-link text-danger" id="delete-cart" data-id="{{ $tempTransaction->product_id }}">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $subtotal += $tempTransaction->product->price * $tempTransaction->quantity;
                $tax += $tempTransaction->product->price * $tempTransaction->quantity * 0.11;
                $total += $tempTransaction->product->price * $tempTransaction->quantity * 1.11;
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
                    <div class="form-group">
                        <label for="total_paid">Bayar</label>
                        <input type="text" class="form-control total_paid" name="total_paid" id="total_paid">
                    </div>
                    <div class="form-group">
                        <label for="total_return">Kembalian</label>
                        <input type="text" class="form-control total_paid" name="total_return" id="total_return">
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
                    Swal.fire({
                        title: 'Berhasil',
                        text: 'Transaksi berhasil diproses',
                        timer: 2000,
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
