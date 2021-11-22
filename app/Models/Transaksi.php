<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        // add other column names here that you want as Carbon Date
    ];

    public function detail_vaksin()
    {
        return $this->belongsTo(DetailVaksin::class);
    }
}
