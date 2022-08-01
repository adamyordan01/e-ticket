@extends('layouts.base', ['title' => 'Edit User'])

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}">
    <style>
        /* 1.18 Select2 */
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            box-shadow: none;
        }

        .select2-container .select2-selection--multiple,
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            min-height: 42px;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-select: none;
            outline: none;
            background-color: #fdfdff;
            border-color: #e4e6fc;
        }

        .select2-dropdown {
            border-color: #e4e6fc !important;
        }

        .select2-container.select2-container--open .select2-selection--multiple {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-container.select2-container--focus .select2-selection--multiple,
        .select2-container.select2-container--focus .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-container.select2-container--open .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-results__option {
            padding: 10px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 7px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            min-height: 42px;
            line-height: 42px;
            padding-left: 15px;
            padding-right: 20px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__arrow,
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 1px;
            right: 1px;
            width: 40px;
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            color: #fff;
            padding-left: 10px;
            padding-right: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-left: 10px;
            padding-right: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-results__option[aria-selected=true],
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6777ef;
            color: #fff;
        }

        .select2-results__option {
            padding-right: 10px 15px;
        }
    </style>
@endpush

@section('section-header')
    <h1>Edit Data Pengguna</h1>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                        <div class="col-md-6">
                            <a href="{{ route('user.index') }}" class="btn btn-link pl-0">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name ?? old('name') }}" placeholder="John Doe">
                            {{-- make error --}}
                            @if ($errors->has('name'))
                                <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="text" name="email" id="email" class="form-control" value="{{ $user->email ?? old('email') }}">
                            {{-- make error --}}
                            @if ($errors->has('email'))
                                <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="{{ $user->username ?? old('username') }}">
                            {{-- make error --}}
                            @if ($errors->has('username'))
                                <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password_show_hide();">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                            @if ($errors->has('password'))
                                <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <label for="">Status</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="status" id="aktif" {{ $user->status == 1 ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="aktif">AKTIF</label>
                        </div>
                        <div class="form-check form-check-inline mb-3">
                            <input type="radio" class="form-check-input" name="status" id="tidak_aktif" {{ $user->status == 0 ? 'checked' : '' }} value="0">
                            <label class="form-check-label" for="tidak_aktif">TIDAK AKTIF</label>
                        </div>
                        @if ($errors->has('status'))
                            <p class="text-danger mt-2">{{ $errors->first('status') }}</p>
                        @endif
                        <div class="form-group mb-0">
                            <label for="">Peran</label>
                        </div>
                        @foreach ($roles as $role)
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="role" id="{{ $role->name }}" {{ $user->role_id == $role->id ? 'checked' : '' }} value="{{ $role->id }}">
                                <label class="form-check-label" for="{{ $role->name }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        @if ($errors->has('role'))
                            <p class="text-danger mt-2">{{ $errors->first('role') }}</p>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary float-right mt-5">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
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