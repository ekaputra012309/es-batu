<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'table_transaksis';

    protected $fillable = [
        'no_inv',
        'customer',
        'total',
        'status',
        'user_id',
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'table_transaksi_id');
    }

    public function bayar()
    {
        return $this->hasMany(Bayar::class, 'table_transaksi_id');
    }

    public function lastBayar()
    {
        return $this->hasOne(Bayar::class, 'table_transaksi_id')->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
