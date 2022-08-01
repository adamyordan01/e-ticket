@if ($status == 1)
    <div class="col-md-12">
        <div class="text-center text-success mb-4">
            <i class="fas fa-check-circle" style="font-size: 4em"></i>
        </div>
        <h3 class="text-center">{{ $detail->product->name }}</h3>
        <h3 class="text-center text-success">TIKET BERHASIL DIVERIFIKASI</h3>
        <p style="font-size: 13pt" class="text-center mb-0">Acara dimulai pada:</p>
        <p style="font-size: 13pt" class="text-center">{{ $detail->product->date_event->format('d-m-Y') }}</p>
    </div>
@elseif ($status == 2)
    <div class="col-md-12">
        <div class="text-center text-warning mb-4">
            <i class="fas fa-hourglass-end" style="font-size: 4em"></i>
        </div>
        <h3 class="text-center">{{ $detail->product->name }}</h3>
        <h3 class="text-center text-warning">ACARA BELUM DIMULAI</h3>
        <p style="font-size: 13pt" class="text-center mb-0">Acara dimulai pada:</p>
        <p style="font-size: 13pt" class="text-center">{{ $detail->product->date_event->format('d-m-Y') }}</p>
    </div>
@elseif ($status == 3)
    <div class="col-md-12">
        <div class="text-center text-warning mb-4">
            <i class="far fa-frown" style="font-size: 4em"></i>
        </div>
        <h3 class="text-center">{{ $detail->product->name }}</h3>
        <h3 class="text-center text-warning">ACARA TELAH BERAKHIR</h3>
        <p style="font-size: 13pt" class="text-center mb-0">Acara dimulai pada:</p>
        <p style="font-size: 13pt" class="text-center">{{ $detail->product->date_event->format('d-m-Y') }}</p>
    </div>
@elseif ($status == 4)
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center text-danger mb-4">
                <i class="fas fa-exclamation-triangle" style="font-size: 4em"></i>
            </div>
            <h3 class="text-center text-danger">TIKET TIDAK TERDAFTAR</h3>
        </div>
    </div>
@endif