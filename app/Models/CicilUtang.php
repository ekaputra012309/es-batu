<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicilUtang extends Model
{
    use HasFactory;

    protected $table = 'cicil_utangs'; // nama tabel
    protected $fillable = [
        'table_utang_id',
        'tanggal',
        'nominal',
        'user_id',
    ];

    // Relasi ke utang besar
    public function utang()
    {
        return $this->belongsTo(UtangBesar::class, 'table_utang_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
