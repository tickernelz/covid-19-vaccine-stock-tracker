<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Barang.
 *
 * @property int $id
 * @property string $nama
 * @property string $kemasan
 * @property int $batch
 * @property string $ed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Barang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereEd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereKemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $tanggal_masuk
 * @property int $stok
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaksi[] $transaksi
 * @property-read int|null $transaksi_count
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereTanggalMasuk($value)
 */
class Barang extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'ed',
        'tanggal_masuk',
        'created_at',
        'updated_at',
        // add other column names here that you want as Carbon Date
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
