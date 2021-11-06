<?php

namespace App\Http\Controllers;

use App\Models\User;
use Crypt;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
            'peran' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get Request
        $get_username = $request->input('username');
        $get_nama = $request->input('nama');
        $get_status = Crypt::decrypt($request->input('peran'));
        $get_password = bcrypt($request->input('password'));

        // Kirim Data ke Database
        $user = new User;
        $user->username = $get_username;
        $user->nama = $get_nama;
        $user->password = $get_password;
        $user->save();
        $user->assignRole($get_status);

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $id)
    {
        $data = User::find($id);

        $request->validate([
            'username' => 'required|string|unique:users,username,'.$data->id,
            'nama' => 'required|string',
            'peran' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get Request
        $get_username = $request->input('username');
        $get_nama = $request->input('nama');
        $get_status = Crypt::decrypt($request->input('peran'));
        $get_password = bcrypt($request->input('password'));

        // Edit Data
        $data->username = $get_username;
        $data->nama = $get_nama;
        if ($data->password !== $get_password) {
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
