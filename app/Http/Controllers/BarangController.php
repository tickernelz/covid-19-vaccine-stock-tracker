<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Get Data
        $data = Barang::get();

        return view('kelola.barang.index', [
            'data' => $data,
        ]);
    }

    public function tambahindex()
    {
        // Config
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        return view('kelola.barang.tambah', [
            'conf_tgl' => $conf_tgl,
        ]);
    }

    public function editindex(Request $request, int $id)
    {
        // Config
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        // Get Data
        $data = Barang::find($id);

        // Konversi Tanggal
        $tanggal_ed = Carbon::parse($data->ed)->formatLocalized('%d %B %Y');
        $tanggal_masuk = Carbon::parse($data->tanggal_masuk)->formatLocalized('%d %B %Y');

        return view('kelola.barang.edit', [
            'data' => $data,
            'tanggal_ed' => $tanggal_ed,
            'tanggal_masuk' => $tanggal_masuk,
            'conf_tgl' => $conf_tgl,
        ]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kemasan' => 'required|string',
            'batch' => 'required|string',
            'ed' => 'required|string',
        ]);

        // Konversi Tanggal
        $tanggal_ed = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('ed'))->format('Y-m-d');
        $tanggal_masuk = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal_masuk'))->format('Y-m-d');

        // Kirim Data ke Database
        $data = new Barang;
        $data->tanggal_masuk = $tanggal_masuk;
        $data->nama = $request->input('nama');
        $data->kemasan = $request->input('kemasan');
        $data->batch = $request->input('batch');
        $data->ed = $tanggal_ed;
        $data->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $id)
    {
        $data = Barang::find($id);

        $request->validate([
            'tanggal_masuk' => 'required|string',
            'nama' => 'required|string',
            'kemasan' => 'required|string',
            'batch' => 'required|string',
            'ed' => 'required|string',
        ]);

        // Konversi Tanggal
        $tanggal_ed = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('ed'))->format('Y-m-d');
        $tanggal_masuk = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal_masuk'))->format('Y-m-d');

        // Edit Data
        $data->tanggal_masuk = $tanggal_masuk;
        $data->nama = $request->input('nama');
        $data->kemasan = $request->input('kemasan');
        $data->batch = $request->input('batch');
        $data->ed = $tanggal_ed;
        $data->save();

        return back()->with('success', 'Data Berhasil Diubah!');
    }

    public function hapus(int $id)
    {
        Barang::find($id)->delete();

        return redirect()->route('index.barang');
    }
}
