<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    // use HasFactory;
    protected $table = 'coa';
    protected $fillable = [
        'kode',
        'nama',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transaksi(){
        return $this->hasMany(Transaksi::class,'id','coa_id');
    }
}
