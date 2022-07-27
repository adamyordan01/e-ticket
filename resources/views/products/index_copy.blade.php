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
    </style>
@endpush

@section('modal')
    <div class="modal fade facultyModal" tabindex="-1" role="dialog" id="productModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="javascript:void(0)" method="POST" id="productForm" name="productForm" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="modal-body">
                        {{-- <div class="form-group">
                            <label for="product_code">Kode Produk</label>
                            <input type="text" name="product_code" id="product_code" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div> --}}
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Produk</label>
                            {{-- <input type="file" name="image" id="image" class="form-control" onchange="readUrl(this)" accept="image/*"> --}}
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <input type="hidden" name="hidden_image" id="hidden_image">
                        <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-save" value="create" class="btn btn-primary">Tambah Data</button>
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
        var siteUrl = '{{ URL::to('') }}';
        console.log(siteUrl);
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
            })

            // when button add clicked
            $('#create-product').click(function () {
                $('#btn-save').val('create-product')
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modal-title').text('Tambah Produk');
                $('#image').attr('required', true);
                $('#productModal').modal('show');
                $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
            });

            // when button edit clicked
            $('body').on('click', '.edit-product', function () {
                var product_id = $(this).data('id');
                $.get('get-products/' + product_id, function (data) {
                    $('#title-error').hide();
                    $('#modal-title').text('Edit Produk');
                    $('#btn-save').val('edit-product');
                    $('#productModal').modal('show');
                    $('#product_id').val(data.id);
                    $('#name').val(data.name);
                    $('#price').val(data.price);
                    $('#modal-preview').attr('alt', 'No image available');
                    if (data.image) {
                        $('#modal-preview').attr('src', siteUrl + 'public/product' + data.image);
                        $('#hidden_image').attr('src', siteUrl + 'public/product' + data.image);
                    }
                });
            });

            // when delete button clicked
            $('body').on('click', '#delete-product', function () {
                var product_id = $(this).data('id');
                if (confirm("Apakah anda yakin akan menghapus product ini?")) {
                    $.ajax({
                        type: 'get',
                        url: siteUrl + '/delete-product/' + product_id,
                        success: function (data) {
                            $('#dataTable').DataTable().ajax.reload();
                        },
                        error: function (data) {
                            alert('Error: ',  data);
                        }
                    });
                }
            });

            // submit
            $('body').on('click', '#productForm', function (e) {
                e.preventDefault();
                // var actionType = $('#btn-save').val();
                // $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: siteUrl + "/products",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#btn-save').html('Simpan Data');
                        $('#productForm').trigger("reset");
                        $('#productModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        $("#btn-save"). attr("disabled", false);
                    },
                    error: function (data) {
                        console.log('Error', data);
                        // $('#btn-save').html('Simpan Data');
                        // $('#btn-save').html('Simpan Data');
                        // $('#productForm').trigger("reset");
                        // $('#productModal').modal('hide');
                        // $('#dataTable').DataTable().ajax.reload();
                        // var error = data.responseJSON.errors;
                        // if (error.name) {
                        //     $('#name-error').show();
                        //     $('#name-error').text(error.name);
                        // }
                        // if (error.price) {
                        //     $('#price-error').show();
                        //     $('#price-error').text(error.price);
                        // }
                        // if (error.image) {
                        //     $('#image-error').show();
                        //     $('#image-error').text(error.image);
                        // }
                    }
                });
            });

            function readUrl (input, id) {
                id = id || '#modal-preview';
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(id).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                    $('#modal-preview').removeClass('hidden');
                    $('#start').hide();
                }
            }

        });

    </script>
@endpush