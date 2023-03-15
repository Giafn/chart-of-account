<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use HasFactory;
    protected $fillable = [
        'nama',
        'indicator',
    ];

    public function coa()
    {
        return $this->hasMany(Coa::class,'id','category_id');
    }
}
