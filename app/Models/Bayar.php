<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    use HasFactory;
    protected $table = 'bayars';

    protected $fillable = [
        'table_transaksi_id',
        'nominal',
        'user_id',
    ];

    public function transaksibayar()
    {
        return $this->belongsTo(Transaksi::class, 'table_transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
