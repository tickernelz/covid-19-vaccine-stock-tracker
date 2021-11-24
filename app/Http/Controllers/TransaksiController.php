<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailVaksin;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Get Data
        $data = Barang::get();

        return view('kelola.transaksi.index', compact([
            'data',
        ]));
    }

    public function lihat_index(int $id)
    {
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        $conf_bulan_tahun = [
            'format' => 'MMMM YYYY',
            'locale' => 'id',
        ];

        // Get Data
        $barang = Barang::firstWhere('id', $id);

        // Sent Data
        $vaksin = DetailVaksin::get();
        if ($vaksin) {
            $daftar_bulan = DetailVaksin::where('barang_id', $id)->pluck('tanggal');
        } else {
            $daftar_bulan = null;
        }

        return view('kelola.transaksi.lihat', compact([
            'daftar_bulan',
            'barang',
            'conf_tgl',
            'conf_bulan_tahun',
        ]));
    }

    public function lihat_cari(Request $request, int $id)
    {
        $conf_tgl = [
            'format' => 'DD MMMM YYYY',
            'locale' => 'id',
        ];

        $conf_bulan_tahun = [
            'format' => 'MMMM YYYY',
            'locale' => 'id',
        ];

        // Get Data
        $tanggal = $request->input('tanggal');
        $barang = Barang::firstWhere('id', $id);
        $detail_vaksin = DetailVaksin::firstWhere([
            ['barang_id', '=', $id],
            ['tanggal', '=', $tanggal],
        ]);
        $transaksi = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->get();

        // Sent Data
        $vaksin = DetailVaksin::get();
        if ($vaksin) {
            $daftar_bulan = DetailVaksin::where('barang_id', $id)->pluck('tanggal');
        } else {
            $daftar_bulan = null;
        }

        // Hitung Stok
        $masuk = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->sum('penerimaan');
        $keluar = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->sum('pengeluaran');
        $total = $masuk - $keluar;

        return view('kelola.transaksi.lihat', compact([
            'daftar_bulan',
            'total',
            'tanggal',
            'barang',
            'detail_vaksin',
            'transaksi',
            'conf_tgl',
            'conf_bulan_tahun',
        ]));
    }

    public function hitung_stok(Request $request, int $id)
    {
        $vaksin = DetailVaksin::where('barang_id', $id)->whereIn('tanggal', $request->input('hitung_stok', []))->get();

        $sum_total = 0;
        foreach ($vaksin as $li) {
            // Hitung Stok
            $masuk = Transaksi::where('detail_vaksin_id', $li->id)->sum('penerimaan');
            $keluar = Transaksi::where('detail_vaksin_id', $li->id)->sum('pengeluaran');
            $total = $masuk - $keluar;
            $sum_total += $total;
        }

        return back()->with('total', $sum_total);
    }

    public function post_detail_vaksin(Request $request, int $id, string $tanggal)
    {
        $request->validate([
            'kemasan' => 'required|string',
            'batch' => 'string|nullable',
            'ed' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $ed = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('ed'))->format('Y-m-d');

        // Kirim Data ke Database
        DetailVaksin::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'barang_id' => $id,
                'kemasan' => $request->input('kemasan'),
                'batch' => $request->input('batch'),
                'tanggal' => $tanggal,
                'ed' => $ed,
            ],
        );

        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function get_transaksi(Request $request)
    {
        $id = $request->id;
        $data = Transaksi::firstWhere('id', $id);
        $tanggal = Carbon::parse($data->tanggal)->formatLocalized('%d %B %Y');

        $response = [
            'tanggal' => $tanggal,
            'data' => $data,
        ];

        return Response()->json($response);
    }

    public function tambah_transaksi(Request $request, int $id)
    {
        $request->validate([
            'tanggal-transaksi' => 'required|string',
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
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal-transaksi'))->format('Y-m-d');

        // Kirim Data ke Database
        $data = new Transaksi;
        $data->detail_vaksin_id = $id;
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

        return back()->with('success-transaksi', 'Transaksi Berhasil Ditambahkan!');
    }

    public function edit_transaksi(Request $request)
    {
        $data = Transaksi::firstWhere('id', $request->input('id-edit'));

        $request->validate([
            'tanggal-transaksi-edit' => 'required|string',
            'dokumen-edit' => 'string|nullable',
            'dari-edit' => 'string|nullable',
            'kepada-edit' => 'string|nullable',
            'penerimaan-edit' => 'integer|nullable',
            'pengeluaran-edit' => 'integer|nullable',
            'petugas-edit' => 'string|nullable',
            'penerima-edit' => 'string|nullable',
            'hp-edit' => 'numeric|nullable',
            'keterangan-edit' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal-transaksi-edit'))->format('Y-m-d');

        // Kirim Data ke Database
        $data->tanggal = $tanggal;
        $data->dokumen = $request->input('dokumen-edit');
        $data->dari = $request->input('dari-edit');
        $data->kepada = $request->input('kepada-edit');
        $data->penerimaan = $request->input('penerimaan-edit');
        $data->pengeluaran = $request->input('pengeluaran-edit');
        $data->petugas = $request->input('petugas-edit');
        $data->penerima = $request->input('penerima-edit');
        $data->hp = $request->input('hp-edit');
        $data->keterangan = $request->input('keterangan-edit');
        $data->save();

        return back()->with('success-transaksi', 'Transaksi Berhasil Diperbarui!');
    }

    public function hapus(int $id)
    {
        Transaksi::firstWhere('id', $id)->delete();

        return back()->with('success-transaksi', 'Transaksi Berhasil Dihapus!');
    }
}
