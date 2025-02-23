<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'table_transaksi_details';

    protected $fillable = [
        'table_transaksi_id',
        'no_inv',
        'berat',
        'qty',
        'harga',
        'satuan',
        'user_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'table_transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
