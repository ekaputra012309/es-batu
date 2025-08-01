<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranHeader extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran_headers';

    protected $fillable = [
        'tanggal',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PengeluaranDetail::class, 'pengeluaran_header_id');
    }
}
