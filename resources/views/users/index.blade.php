@extends('layouts.base', ["title" => "Daftar Pengguna"])

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">
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

        table {
            width: 100% !important;
        }
    </style>
@endpush

@section('modal')
    {{-- add role --}}
    <div class="modal fade user-modal" tabindex="-1" role="dialog" id="user-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST" id="form-add-user">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Pengguna</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control">
                            <span class="text-danger mt-1 error-text username_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" class="form-control">
                            <span class="text-danger mt-1 error-text email_error"></span>
                        </div>
                        <div class="form-group mb-0">
                            <label for="">Peran</label>
                        </div>
                        @foreach ($roles as $role)
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="role" id="{{ $role->name }}" value="{{ $role->id }}">
                                <label class="form-check-label" for="{{ $role->name }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        <span class="text-danger mt-1 error-text role_error"></span>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password_show_hide();">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                            <span class="text-danger error-text password_error"></span>
                        </div>
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
    <div class="modal fade editRoleModal" tabindex="-1" role="dialog" id="editRoleModal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('role.update') }}" method="POST" id="editRole">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="role_id">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
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
        <div class="breadcrumb-item">Daftar Pengguna</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-user" data-toggle="modal" data-target="#user-modal">Tambah Pengguna</button>
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
                                    <th style="width: 5%">#</th>
                                    <th>Nama</th>
                                    <th>E-Mail</th>
                                    <th>Peran</th>
                                    <th>Status</th>
                                    <th>Dibuat pada</th>
                                    <th>Diupdate pada</th>
                                    <th style="width: 10%">Aksi</th>
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
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.get-users') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            // tambah data
            $('#form-add-user').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    method: form.attr('method'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#btn-save').text('Tambah Data...');
                        $('#btn-save').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            })
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#user-modal').modal('hide');
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#form-add-user')[0].reset();
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                });
            });



        });

        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            } else {
                x.type = "password";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";
            }
        }
    </script>
@endpush