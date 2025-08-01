<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranDetail extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran_details';

    protected $fillable = [
        'pengeluaran_header_id',
        'nominal',
        'keterangan',
    ];

    public function header()
    {
        return $this->belongsTo(PengeluaranHeader::class, 'pengeluaran_header_id');
    }
}
