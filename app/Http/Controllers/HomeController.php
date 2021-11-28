<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiKabupaten;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $vaksin = Barang::count();
        $list_vaksin = Barang::get();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $penerimaan = Transaksi::whereBetween('tanggal',[$start,$end])->get()->sum('penerimaan');
        $penerimaan = (int)$penerimaan;
        $pengeluaran = TransaksiKabupaten::whereBetween('tanggal',[$start,$end])->get()->sum('penerimaan');
        $pengeluaran = (int)$pengeluaran;
        $sisa = $penerimaan - $pengeluaran;
        return view('home', compact([
            'list_vaksin',
            'vaksin',
            'penerimaan',
            'pengeluaran',
            'sisa',
        ]));
    }

    public function transaksi(Request $request) {
        (int)$id  = $request->input('vaksin') ;
        return redirect()->route('lihat.index.transaksi', $id) ;
    }
}
