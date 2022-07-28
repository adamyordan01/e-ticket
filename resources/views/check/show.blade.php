@if ($status == 0)
    <div class="col-md-12">
        <div class="text-center text-success mb-4">
            <i class="fas fa-check-circle" style="font-size: 4em"></i>
        </div>
        <h3 class="text-center text-success">TIKET BERHASIL DIVERIFIKASI</h3>
    </div>
@elseif ($status == 1)
    <div class="col-md-12">
        <div class="text-center text-warning mb-4">
            <i class="fas fa-exclamation" style="font-size: 4em"></i>
        </div>
        <h3 class="text-center text-warning">TIKET SUDAH DIGUNAKAN</h3>
    </div>
@elseif ($status == 2)
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center text-danger mb-4">
                <i class="fas fa-exclamation-triangle" style="font-size: 4em"></i>
            </div>
            <h3 class="text-center text-danger">TIKET TIDAK TERDAFTAR</h3>
        </div>
    </div>
@endif