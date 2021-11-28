<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Get Data
        $data = Barang::get();

        return view('kelola.barang.index', compact([
            'data',
        ]));
    }

    public function tambahindex()
    {
        return view('kelola.barang.tambah', compact([
        ]));
    }

    public function editindex(int $id)
    {
        // Get Data
        $data = Barang::find($id);

        return view('kelola.barang.edit', compact([
            'data',
        ]));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:barangs',
        ]);

        // Kirim Data ke Database
        $data = new Barang;
        $data->nama = $request->input('nama');
        $data->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $id)
    {
        $data = Barang::find($id);

        $request->validate([
            'nama' => 'required|string|unique:barangs,nama,'.$data->id,
        ]);

        // Edit Data
        $data->nama = $request->input('nama');
        $data->save();

        return back()->with('success', 'Data Berhasil Diubah!');
    }

    public function hapus(int $id)
    {
        Barang::whereId($id)->delete();

        return redirect()->route('index.barang');
    }
}
