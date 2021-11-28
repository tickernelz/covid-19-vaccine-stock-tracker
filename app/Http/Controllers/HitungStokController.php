<?php

namespace App\Http\Controllers;

use App\Models\DetailVaksin;
use App\Models\Transaksi;
use App\Models\TransaksiKabupaten;
use Illuminate\Http\Request;

class HitungStokController extends Controller
{
    public function index()
    {
        // Sent Data
        $vaksin = DetailVaksin::select('tanggal')->groupBy('tanggal')->get();
        if ($vaksin) {
            $daftar_bulan = $vaksin->pluck('tanggal')->sort();
        } else {
            $daftar_bulan = null;
        }

        return view('kelola.hitung_stok.index', compact([
            'daftar_bulan',
        ]));
    }

    public function hitung_stok_total(Request $request)
    {
        $vaksin = DetailVaksin::whereIn('tanggal', $request->input('hitung_stok', []))->get();

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

    public function hitung_stok_penerimaan(Request $request)
    {
        $vaksin = DetailVaksin::whereIn('tanggal', $request->input('hitung_stok', []))->get();

        $sum_total = 0;
        foreach ($vaksin as $li) {
            // Hitung Stok
            $masuk = Transaksi::where('detail_vaksin_id', $li->id)->sum('penerimaan');
            $sum_total += $masuk;
        }

        return back()->with('total-penerimaan', $sum_total);
    }

    public function hitung_stok_pengeluaran(Request $request)
    {
        $vaksin = DetailVaksin::whereIn('tanggal', $request->input('hitung_stok', []))->get();

        $sum_total = 0;
        foreach ($vaksin as $li) {
            // Hitung Stok
            $keluar = TransaksiKabupaten::where('detail_vaksin_id', $li->id)->sum('penerimaan');
            $sum_total += $keluar;
        }

        return back()->with('total-pengeluaran', $sum_total);
    }
}
