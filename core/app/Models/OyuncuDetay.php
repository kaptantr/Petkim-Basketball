<?php

namespace App\Models;

use Eloquent as Model;

class OyuncuDetay extends Model
{

    public $table = 'oyuncu_detaylar';

    public $timestamps = false;

    public $fillable = [
        'adsoyad',
        'sezon',
        'sure',
        'sayi',
        'SA',
        'S2',
        'S3',
        'SR',
        'HR',
        'TR',
        'AST',
        'TÇ',
        'TK',
        'BL',
        'FA',
        'VP',

    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'adsoyad' => 'string',
        'sezon' => 'string',
        'sure' => 'datetime',
        'sayi' => 'integer',
        'SA' => 'string',
        'S2' => 'string',
        'S3' => 'string',
        'SR' => 'float',
        'HR' => 'float',
        'TR' => 'float',
        'AST' => 'float',
        'TÇ' => 'float',
        'TK' => 'float',
        'BL' => 'float',
        'FA' => 'float',
        'VP' => 'float',
    ];


    public $rules = [
        'adsoyad' => 'nullable|string|max:100',
        'sezon' => 'string',
        'sure' => 'time',
        'sayi' => 'integer',
        'SA' => 'nullable|string|max:10',
        'S2' => 'nullable|string|max:10',
        'S3' => 'nullable|string|max:10',
        'SR' => 'float',
        'HR' => 'float',
        'TR' => 'float',
        'AST' => 'float',
        'TÇ' => 'float',
        'TK' => 'float',
        'BL' => 'float',
        'FA' => 'float',
        'VP' => 'float',
    ];

    protected $relationships = [ ];
}
