<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{
    use HasFactory;
    protected $table = 'slips';

    protected $fillable = [
        'tanggal',
        'hari_kerja',
        'nama',
        'gp',
        'inssentif',
        'bonus',
        'uang_makan',
        'pot_uang_makan',
        'pot_kasbon',
        'hutang',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
