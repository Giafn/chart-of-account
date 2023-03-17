<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    // use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = [
        'coa_id',
        'desc',
        'nominal',
        'created_at',
    ];

    public function coa(){
        return $this->belongsTo(Coa::class);
    }

}
