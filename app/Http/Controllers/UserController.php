<?php

namespace App\Http\Controllers;

use App\Models\User;
use Crypt;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        // Get Data
        $data = User::with('roles')->get();

        return view('kelola.users.index', [
            'data' => $data,
        ]);
    }

    public function tambahindex()
    {
        // Get Data
        $roles = Role::all();

        return view('kelola.users.tambah', [
            'roles' => $roles,
        ]);
    }

    public function editindex(Request $request, int $id)
    {
        // Get Data
        $data = User::with('roles')->find($id);
        $roles = Role::all();

        return view('kelola.users.edit', [
            'data' => $data,
            'roles' => $roles,
        ]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'nama' => 'required|string',
            'nip' => 'required|unique:users',
            'peran' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get Request
        $get_username = $request->input('username');
        $get_nama = $request->input('nama');
        $get_nip = $request->input('nip');
        $get_status = Crypt::decrypt($request->input('peran'));
        $get_password = bcrypt($request->input('password'));

        // Kirim Data ke Database
        $user = new User;
        $user->username = $get_username;
        $user->nama = $get_nama;
        $user->nip = $get_nip;
        $user->password = $get_password;
        $user->save();
        $user->assignRole($get_status);

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $id)
    {
        $nipori = User::find($id)->nip;
        $nipedit = $request->input('nip');
        $usernameori = User::find($id)->username;
        $usernameedit = $request->input('username');
        $passori = User::find($id)->password;
        $passedit = $request->input('password');

        $rules1 = [
            'username' => 'required|string',
            'nama' => 'required|string',
            'nip' => 'required',
            'peran' => 'required|string',
            'password' => 'required|string',
        ];

        $rules2 = [
            'username' => 'required|string|unique:users',
            'nama' => 'required|string',
            'nip' => 'required|unique:users',
            'peran' => 'required|string',
            'password' => 'required|string',
        ];

        $rules3 = [
            'username' => 'required|string',
            'nama' => 'required|string',
            'nip' => 'required|unique:users',
            'peran' => 'required|string',
            'password' => 'required|string',
        ];

        $rules4 = [
            'username' => 'required|string|unique:users',
            'nama' => 'required|string',
            'nip' => 'required',
            'peran' => 'required|string',
            'password' => 'required|string',
        ];

        $messages = [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username harus beda dari yang lain',
            'username.string' => 'Username tidak valid',
            'nama.required' => 'Nama wajib diisi',
            'nama.string' => 'Nama tidak valid',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP harus beda dari yang lain',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password harus berupa string',
        ];

        if ($nipori === $nipedit && $usernameori === $usernameedit) {
            return $this->extracted($request, $rules1, $messages, $id, $passori, $passedit);
        }

        if ($nipori === $nipedit) {
            return $this->extracted($request, $rules4, $messages, $id, $passori, $passedit);
        }

        if ($usernameori === $usernameedit) {
            return $this->extracted($request, $rules3, $messages, $id, $passori, $passedit);
        }

        return $this->extracted($request, $rules2, $messages, $id, $passori, $passedit);
    }

    public function extracted(Request $request, array $rules1, array $messages, int $id, $passori, $passedit): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), $rules1, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        // Get Request
        $get_username = $request->input('username');
        $get_nama = $request->input('nama');
        $get_nip = $request->input('nip');
        $get_status = Crypt::decrypt($request->input('peran'));
        $get_password = bcrypt($request->input('password'));

        // Edit Data
        $data = User::where('id', $id)->first();
        $data->username = $get_username;
        $data->nama = $get_nama;
        $data->nip = $get_nip;
        if ($passori !== $passedit) {
            $data->password = $get_password;
        }
        $data->save();
        $data->assignRole($get_status);

        return back()->with('success', 'Data Berhasil Diubah!');
    }

    public function hapus(int $id)
    {
        User::find($id)->delete();

        return redirect()->route('index.user');
    }
}
