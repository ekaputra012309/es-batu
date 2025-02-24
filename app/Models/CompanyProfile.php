<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'company_profiles';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'description',
        'image',
    ];
}