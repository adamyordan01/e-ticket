<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::select(['id', 'name'])->get();
        return view('users.index', [
            'roles' => $roles
        ]);
    }

    public function getUSers()
    {
        // make query get users relation to roles
        // $users = User;
        $users = DB::select("
            SELECT
                users.id,
                users.`name`,
                users.username,
                users.email,
                users.status,
                users.created_at,
                users.updated_at,
                roles.`name` AS role 
            FROM
                users
                INNER JOIN roles ON users.role_id = roles.id
        ");

        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Produk" data-id="'.$user->id.'" id="editButton" class="btn btn-info mr-2"><i class="fas fa-edit"></i></button>';
                $buttonDelete = '<button data-toggle="tooltip" data-placement="top" title="Delete Produk" data-id="'.$user->id.'" id="deleteButton" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';
                return $buttonEdit . $buttonDelete;
            })
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->format('d-m-Y');
            })
            ->editColumn('updated_at', function ($user) {
                return Carbon::parse($user->updated_at)->format('d-m-Y');
            })
            ->addColumn('status', function ($user) {
                if ($user->status == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-dark">Tidak Aktif</span>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ], [
            'name.required' => 'Nama User harus diisi',
            'name.max' => 'Nama User maksimal 255 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->role;
            $user->status = 1;
            $user->save();
            return response()->json([
                'code' => 1,
                'message' => 'Pengguna berhasil ditambahkan'
            ]);
        }
    }
}
