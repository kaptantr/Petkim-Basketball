<?php

namespace App\Models;

use Eloquent as Model;

class MacSonuc extends Model
{
    public $timestamps = false;

    public $table = 'mac_sonuclari';

    public $fillable = [
        'Tarih',
        'Saat',
        'Mac_No',
        'Hafta',
        'Lig',
        'A_Takim',
        'Sonuc',
        'B_Takim',
        'Grubu',
        'Sehir',
        'Salon',
        'TV',
        'Bas_Hakem',
        '1_Yrd_Hakem',
        '2_Yrd_Hakem',
        '1_Masa_Gorevlisi',
        '2_Masa_Gorevlisi',
        '3_Masa_Gorevlisi',
        '4_Masa_Gorevlisi',

    ];

    protected $hidden = [
    ];

    protected $casts = [
        'id' => 'integer',
        'Tarih' => 'date',
        'Saat' => 'datetime',
        'Mac_No' => 'string',
        'Hafta' => 'string',
        'Lig' => 'string',
        'A_Takim' => 'string',
        'Sonuc' => 'string',
        'B_Takim' => 'string',
        'Grubu' => 'string',
        'Sehir' => 'string',
        'Salon' => 'string',
        'TV' => 'string',
        'Bas_Hakem' => 'string',
        '1_Yrd_Hakem' => 'string',
        '2_Yrd_Hakem' => 'string',
        '1_Masa_Gorevlisi' => 'string',
        '2-Masa_Gorevlisi' => 'string',
        '3_Masa_Gorevlisi' => 'string',
        '4_Masa_Gorevlisi' => 'string',
    ];


    protected $rules = [
        'Tarih' => 'date',
        'Saat' => 'datetime',
        'Mac_No' => 'string',
        'Hafta' => 'nullable|string|max:20',
        'Lig' => 'nullable|string|max:10',
        'A_Takim' => 'nullable|string|max:100',
        'Sonuc' => 'nullable|string|max:10',
        'B_Takim' => 'nullable|string|max:100',
        'Grubu' => 'nullable|string|max:25',
        'Sehir' => 'nullable|string|max:50',
        'Salon' => 'nullable|string|max:100',
        'TV' => 'nullable|string|max:25',
        'Bas_Hakem' => 'nullable|string|max:100',
        '1_Yrd_Hakem' => 'nullable|string|max:100',
        '2_Yrd_Hakem' => 'nullable|string|max:100',
        '1_Masa_Gorevlisi' => 'nullable|string|max:100',
        '2_Masa_Gorevlisi' => 'nullable|string|max:100',
        '3_Masa_Gorevlisi' => 'nullable|string|max:100',
        '4_Masa_Gorevlisi' => 'nullable|string|max:100',
    ];

    protected $relationships = [ ];
}
