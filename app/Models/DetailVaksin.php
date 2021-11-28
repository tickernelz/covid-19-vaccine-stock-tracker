<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailVaksin extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function transaksi_kabupatens()
    {
        return $this->hasMany(TransaksiKabupaten::class);
    }
}
