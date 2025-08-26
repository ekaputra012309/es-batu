<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtangBesar extends Model
{
    use HasFactory;

    protected $table = 'utang_besars';

    protected $fillable = [
        'table_transaksi_id',
        'tanggal',
        'nominal',
        'user_id',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke TableTransaksi
    public function transaksi()
    {
        return $this->belongsTo(TableTransaksi::class, 'table_transaksi_id');
    }

    // Relasi ke CicilUtang
    public function cicilans()
    {
        return $this->hasMany(CicilUtang::class, 'table_utang_id');
    }

    // Accessor untuk status (biar gampang dipanggil)
    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Lunas' : 'Belum Lunas';
    }
}
