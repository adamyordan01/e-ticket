@extends('layouts.base', ["title" => "Daftar Produk"])

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
    </style>
@endpush

@section('modal')
    {{-- add product --}}
    <div class="modal fade productModal" tabindex="-1" role="dialog" id="productModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" id="addProduct" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <span class="text-danger mt-1 error-text price_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Produk</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-save" value="create" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit product --}}
    <div class="modal fade productModal" tabindex="-1" role="dialog" id="editProductModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.update') }}" method="POST" id="editProduct" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="product_id">
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <span class="text-danger mt-1 error-text price_error"></span>
                        </div>
                        {{-- make radio status --}}
                        <div class="form-group mb-0">
                            <label for="">Status</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="status" id="aktif" value="1">
                            <label class="form-check-label" for="aktif">AKTIF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="status" id="tidak_aktif" value="0">
                            <label class="form-check-label" for="tidak_aktif">TIDAK AKTIF</label>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Produk</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger mt-1 error-text image_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-update" value="update" class="btn btn-primary">Ubah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Daftar Produk</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-product" data-toggle="modal" data-target="#productModal">Tambah Produk</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
            $("#dataTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.get-products') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'image', name: 'image'},
                    {data: 'product_code', name: 'product_code'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            $('#image').change(function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#modal-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            });

            // Tambah data
            $('#addProduct').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: new FormData(form),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#btn-save').text('Saving...');
                        $('#btn-save').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#productModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#addProduct')[0].reset();
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                })
            })

            // Edit data
            $(document).on('click', '#editButton', function () {
                let product_id = $(this).data('id');
                $('#editProductModal').find('form')[0].reset();
                $('#editProductModal').find('span.error-text').text('');
                $('#editProductModal').find('#hidden_image').val('');
                $.ajax({
                    url: "{{ route('products.get-product-detail') }}",
                    type: 'POST',
                    data: {
                        product_id: product_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#editProductModal').find('input[name="product_id"]').val(response.detail.id);
                        $('#editProductModal').find('#name').val(response.detail.name);
                        $('#editProductModal').find('#price').val(response.detail.price);
                        if (response.detail.status == 0) {
                            $('#tidak_aktif').prop('checked', true);
                        } else {
                            $('#aktif').prop('checked', true);
                        }
                        $('#editProductModal').find('#hidden_image').val(response.detail.image);
                        $('#editProductModal').find('#modal-preview').attr('src', '{{ asset('product') }}/' + response.detail.image);
                        $('#editProductModal').modal('show');
                    }
                })
            })

            // update data
            $('#editProduct').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(this);
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: new FormData(form),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#btn-update').text('Saving...');
                        $('#btn-update').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#btn-update').text('Update Data');
                            $('#btn-update').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#editProductModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            $('#btn-update').text('Update Data');
                            $('#btn-update').attr('disabled', false);
                            $('#editProduct')[0].reset();
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                })
            });

        });

    </script>
@endpush