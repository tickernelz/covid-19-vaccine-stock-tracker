<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailVaksin;
use App\Models\Transaksi;
use App\Models\TransaksiKabupaten;
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
        if ($request->has('ubah'))
        {
            $detail_vaksin = DetailVaksin::firstWhere([
                ['barang_id', '=', $id],
                ['tanggal', '=', $request->input('tanggal_lama')],
            ]);
            $detail_vaksin->tanggal = $request->input('tanggal');
            $detail_vaksin->save();
            return back()->with('success-ubah-tanggal', 'Tanggal Data Berhasil Diubah!');
        }
        $transaksi = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->get();
        if($transaksi->isEmpty())
        {
            $provinsi_tanggal = null;
            $provinsi_dari = null;
        } else {
            $provinsi_tanggal = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->select('tanggal')->groupBy('tanggal')->get();
            $provinsi_dari = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->select('dari')->groupBy('dari')->get();
        }
        $transaksi_kabupaten = TransaksiKabupaten::where('detail_vaksin_id', $detail_vaksin->id ?? null)->get();

        // Sent Data
        $vaksin = DetailVaksin::get();
        if ($vaksin) {
            $daftar_bulan = DetailVaksin::where('barang_id', $id)->pluck('tanggal');
        } else {
            $daftar_bulan = null;
        }

        // Hitung Stok
        $masuk = Transaksi::where('detail_vaksin_id', $detail_vaksin->id ?? null)->sum('penerimaan');
        $keluar = TransaksiKabupaten::where('detail_vaksin_id', $detail_vaksin->id ?? null)->sum('penerimaan');
        $total = $masuk - $keluar;

        return view('kelola.transaksi.lihat', compact([
            'daftar_bulan',
            'total',
            'provinsi_tanggal',
            'provinsi_dari',
            'tanggal',
            'barang',
            'detail_vaksin',
            'transaksi',
            'transaksi_kabupaten',
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
            $keluar = TransaksiKabupaten::where('detail_vaksin_id', $li->id)->sum('penerimaan');
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

    public function get_transaksi_kabupaten(Request $request)
    {
        $id = $request->id;
        $data = TransaksiKabupaten::firstWhere('id', $id);
        $tanggal = Carbon::parse($data->tanggal)->formatLocalized('%d %B %Y');
        $tanggal_provinsi = Carbon::parse($data->tanggal_provinsi)->formatLocalized('%d %B %Y');

        $response = [
            'tanggal_provinsi' => $tanggal_provinsi,
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
            'dari' => 'required|string',
            'penerimaan' => 'required|integer',
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
        $data->penerimaan = $request->input('penerimaan');
        $data->petugas = $request->input('petugas');
        $data->penerima = $request->input('penerima');
        $data->hp = $request->input('hp');
        $data->keterangan = $request->input('keterangan');
        $data->save();

        return back()->with('success-transaksi', 'Transaksi Berhasil Ditambahkan!');
    }

    public function tambah_transaksi_kabupaten(Request $request, int $id)
    {
        $request->validate([
            'tanggal-transaksi' => 'required|string',
            'tanggal_provinsi' => 'required|string',
            'dokumen' => 'string|nullable',
            'dari' => 'required|string',
            'kepada' => 'required|string',
            'penerimaan' => 'required|integer',
            'petugas' => 'string|nullable',
            'penerima' => 'string|nullable',
            'hp' => 'numeric|nullable',
            'keterangan' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal-transaksi'))->format('Y-m-d');

        // Kirim Data ke Database
        $data = new TransaksiKabupaten();
        $data->detail_vaksin_id = $id;
        $data->tanggal = $tanggal;
        $data->tanggal_provinsi = $request->input('tanggal_provinsi');
        $data->dokumen = $request->input('dokumen');
        $data->dari = $request->input('dari');
        $data->kepada = $request->input('kepada');
        $data->penerimaan = $request->input('penerimaan');
        $data->petugas = $request->input('petugas');
        $data->penerima = $request->input('penerima');
        $data->hp = $request->input('hp');
        $data->keterangan = $request->input('keterangan');
        $data->save();

        return back()->with('success-transaksi-kabupaten', 'Transaksi Berhasil Ditambahkan!');
    }

    public function edit_transaksi_kabupaten(Request $request)
    {
        $data = TransaksiKabupaten::firstWhere('id', $request->input('id-kabupaten-edit'));

        $request->validate([
            'tanggal-transaksi-edit' => 'required|string',
            'tanggal_provinsi-edit' => 'required|string',
            'dokumen-kabupaten-edit' => 'string|nullable',
            'dari-kabupaten-edit' => 'required|string',
            'kepada-kabupaten-edit' => 'required|string',
            'penerimaan-kabupaten-edit' => 'required|integer',
            'petugas-kabupaten-edit' => 'string|nullable',
            'penerima-kabupaten-edit' => 'string|nullable',
            'hp-kabupaten-edit' => 'numeric|nullable',
            'keterangan-kabupaten-edit' => 'string|nullable',
        ]);

        // Konversi Tanggal
        $tanggal = Carbon::createFromLocaleIsoFormat('D MMMM Y', 'id', $request->input('tanggal-transaksi-edit'))->format('Y-m-d');

        // Kirim Data ke Database
        $data->tanggal = $tanggal;
        $data->tanggal_provinsi = $request->input('tanggal_provinsi-edit');
        $data->dokumen = $request->input('dokumen-kabupaten-edit');
        $data->dari = $request->input('dari-kabupaten-edit');
        $data->kepada = $request->input('kepada-kabupaten-edit');
        $data->penerimaan = $request->input('penerimaan-kabupaten-edit');
        $data->petugas = $request->input('petugas-kabupaten-edit');
        $data->penerima = $request->input('penerima-kabupaten-edit');
        $data->hp = $request->input('hp-kabupaten-edit');
        $data->keterangan = $request->input('keterangan-kabupaten-edit');
        $data->save();

        return back()->with('success-transaksi-kabupaten', 'Transaksi Berhasil Diperbarui!');
    }

    public function edit_transaksi(Request $request)
    {
        $data = Transaksi::firstWhere('id', $request->input('id-edit'));

        $request->validate([
            'tanggal-transaksi-edit' => 'required|string',
            'dokumen-edit' => 'string|nullable',
            'dari-edit' => 'required|string',
            'penerimaan-edit' => 'required|integer',
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
        $data->penerimaan = $request->input('penerimaan-edit');
        $data->petugas = $request->input('petugas-edit');
        $data->penerima = $request->input('penerima-edit');
        $data->hp = $request->input('hp-edit');
        $data->keterangan = $request->input('keterangan-edit');
        $data->save();

        return back()->with('success-transaksi', 'Transaksi Berhasil Diperbarui!');
    }

    public function hapus(int $vaksin_id, int $id)
    {
        $transaksi = Transaksi::firstWhere('id', $id);
        $transaksi_kabupaten= TransaksiKabupaten::where([
            ['detail_vaksin_id', '=', $vaksin_id],
            ['tanggal_provinsi', '=', $transaksi->tanggal],
            ['dari', '=', $transaksi->dari],
        ])->get();
        foreach ($transaksi_kabupaten as $li)
        {
            $li->delete();
        }
        $transaksi->delete();

        return back()->with('success-transaksi', 'Transaksi Berhasil Dihapus!');
    }

    public function hapus_kabupaten(int $id)
    {
        TransaksiKabupaten::firstWhere('id', $id)->delete();

        return back()->with('success-transaksi-kabupaten', 'Transaksi Berhasil Dihapus!');
    }
}
