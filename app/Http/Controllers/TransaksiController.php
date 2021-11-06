<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Get Data
        $data = Barang::get();

        return view('kelola.transaksi.index', [
            'data' => $data,
        ]);
    }

    public function lihat_index(int $id)
    {
        // Get Data
        $barang = Barang::find($id);
        $transaksi = Transaksi::where('barang_id', $id)->get();

        // Hitung Stok
        $masuk = Transaksi::where('barang_id', $id)->sum('penerimaan');
        $keluar = Transaksi::where('barang_id', $id)->sum('pengeluaran');
        $total = $masuk - $keluar;

        return view('kelola.transaksi.lihat', [
            'barang' => $barang,
            'transaksi' => $transaksi,
            'total' => $total,
        ]);
    }

    public function tambahindex(int $id)
    {
        // Config
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        // Hitung Stok
        $masuk = Transaksi::where('barang_id', $id)->sum('penerimaan');
        $keluar = Transaksi::where('barang_id', $id)->sum('pengeluaran');
        $total = $masuk - $keluar;

        // Get Data
        $barang = Barang::find($id);

        return view('kelola.transaksi.tambah', [
            'barang' => $barang,
            'conf_tgl' => $conf_tgl,
            'total' => $total,
        ]);
    }

    public function editindex(int $barang_id, int $id)
    {
        // Config
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        // Hitung Stok
        $masuk = Transaksi::where('barang_id', $barang_id)->sum('penerimaan');
        $keluar = Transaksi::where('barang_id', $barang_id)->sum('pengeluaran');
        $total = $masuk - $keluar;

        // Get Data
        $barang = Barang::find($barang_id);
        $transaksi = Transaksi::find($id);

        // Konversi Tanggal
        $tanggal = Carbon::parse($transaksi->tanggal)->formatLocalized('%d %B %Y');

        return view('kelola.transaksi.edit', [
            'barang' => $barang,
            'conf_tgl' => $conf_tgl,
            'total' => $total,
            'transaksi' => $transaksi,
            'tanggal' => $tanggal,
        ]);
    }

    public function tambah(Request $request, int $id)
    {
        $request->validate([
            'tanggal' => 'required|string',
            'dokumen' => 'string|nullable',
            'dari' => 'string|nullable',
            'kepada' => 'string|nullable',
            'penerimaan' => 'integer|nullable',
            'pengeluaran' => 'integer|nullable',
            'petugas' => 'string|nullable',
            'penerima' => 'string|nullable',
            'hp' => 'numeric|nullable',
            'keterangan' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal'))->format('Y-m-d');

        // Kirim Data ke Database
        $data = new Transaksi;
        $data->barang_id = $id;
        $data->tanggal = $tanggal;
        $data->dokumen = $request->input('dokumen');
        $data->dari = $request->input('dari');
        $data->kepada = $request->input('kepada');
        $data->penerimaan = $request->input('penerimaan');
        $data->pengeluaran = $request->input('pengeluaran');
        $data->petugas = $request->input('petugas');
        $data->penerima = $request->input('penerima');
        $data->hp = $request->input('hp');
        $data->keterangan = $request->input('keterangan');
        $data->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $barang_id, int $id)
    {
        $request->validate([
            'tanggal' => 'required|string',
            'dokumen' => 'string|nullable',
            'dari' => 'string|nullable',
            'kepada' => 'string|nullable',
            'penerimaan' => 'integer|nullable',
            'pengeluaran' => 'integer|nullable',
            'petugas' => 'string|nullable',
            'penerima' => 'string|nullable',
            'hp' => 'numeric|nullable',
            'keterangan' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal'))->format('Y-m-d');

        // Kirim Data ke Database
        $data = Transaksi::find($id);
        $data->tanggal = $tanggal;
        $data->dokumen = $request->input('dokumen');
        $data->dari = $request->input('dari');
        $data->kepada = $request->input('kepada');
        $data->penerimaan = $request->input('penerimaan');
        $data->pengeluaran = $request->input('pengeluaran');
        $data->petugas = $request->input('petugas');
        $data->penerima = $request->input('penerima');
        $data->hp = $request->input('hp');
        $data->keterangan = $request->input('keterangan');
        $data->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function hapus(int $barang_id, int $id)
    {
        Transaksi::find($id)->delete();

        return redirect()->back();
    }
}
