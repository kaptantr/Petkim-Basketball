<?php

namespace App\Models;

use Eloquent as Model;

class Istatistik extends Model
{
    public $timestamps = false;

    public $table = 'istatistikler';

    public $fillable = [
        'adsoyad',
        'rakip_takim',
        'tarih',
        'sure',
        'sayi',
        'AG',
        'AG_deger',
        'SA',
        'SA_deger',
        'S2',
        'S2_deger',
        'S3',
        'S3_deger',
        'SR',
        'SR_deger',
        'HR',
        'HR_deger',
        'TR',
        'TR_deger',
        'AST',
        'AS_deger',
        'TÇ',
        'TÇ_deger',
        'TK',
        'TK_deger',
        'BL',
        'BL_deger',
        'FA',
        'FA_deger',
        'VP',
        'VP_deger',
        'sezon',
        'lig',
        'kapsam',
        'takimi',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'id' => 'integer',
        'adsoyad' => 'string',
        'rakip_takim' => 'string',
        'tarih' => 'date',
        'sure' => 'datetime',
        'sayi' => 'integer',
        'AG' => 'string',
        'AG_deger' => 'float',
        'SA' => 'string',
        'SA_deger' => 'float',
        'S2' => 'string',
        'S2_deger' => 'float',
        'S3' => 'string',
        'S3_deger' => 'float',
        'SR' => 'string',
        'SR_deger' => 'float',
        'HR' => 'string',
        'HR_deger' => 'float',
        'TR' => 'string',
        'TR_deger' => 'float',
        'AST' => 'string',
        'AS_deger' => 'float',
        'TÇ' => 'string',
        'TÇ_deger' => 'float',
        'TK' => 'string',
        'TK_deger' => 'float',
        'BL' => 'string',
        'BL_deger' => 'float',
        'FA' => 'string',
        'FA_deger' => 'float',
        'VP' => 'string',
        'VP_deger' => 'float',
        'sezon' => 'string',
        'lig' => 'string',
        'kapsam' => 'string',
        'takimi' => 'string',
    ];


    protected $rules = [
        'adsoyad' => 'nullable|string|max:100',
        'rakip_takim' => 'nullable|string|max:100',
        'tarih' => 'date',
        'sure' => 'time',
        'sayi' => 'integer',
        'AG' => 'nullable|string|max:100',
        'AG_deger' => 'float',
        'SA' => 'float',
        'SA_deger' => 'nullable|string|max:10',
        'S2' => 'float',
        'S2_deger' => 'nullable|string|max:10',
        'S3' => 'float',
        'S3_deger' => 'nullable|string|max:10',
        'SR' => 'float',
        'SR_deger' => 'nullable|string|max:10',
        'HR' => 'float',
        'HR_deger' => 'nullable|string|max:10',
        'TR' => 'float',
        'TR_deger' => 'nullable|string|max:10',
        'AST' => 'float',
        'AS_deger' => 'nullable|string|max:10',
        'TÇ' => 'float',
        'TÇ_deger' => 'nullable|string|max:10',
        'TK' => 'float',
        'TK_deger' => 'nullable|string|max:10',
        'BL' => 'float',
        'BL_deger' => 'nullable|string|max:10',
        'FA' => 'float',
        'FA_deger' => 'nullable|string|max:10',
        'VP' => 'float',
        'VP_deger' => 'nullable|string|max:10',
        'sezon' => 'nullable|string|max:20',
        'lig' => 'nullable|string|max:100',
        'kapsam' => 'nullable|string|max:20',
        'takimi' => 'nullable|string|max:100',
    ];

    protected $relationships = [ ];
}
