<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaksi.
 *
 * @property int $id
 * @property int $barang_id
 * @property string $tanggal
 * @property string|null $dokumen
 * @property string|null $dari
 * @property string|null $kepada
 * @property int|null $penerimaan
 * @property int|null $pengeluaran
 * @property string|null $petugas
 * @property string|null $penerima
 * @property string|null $hp
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Barang $barang
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereBarangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereDari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereKepada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
        // add other column names here that you want as Carbon Date
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
