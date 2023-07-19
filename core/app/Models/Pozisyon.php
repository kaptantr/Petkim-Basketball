<?php

namespace App\Models;

use Eloquent as Model;

class Pozisyon extends Model
{
    public $timestamps = false;

    public $table = 'pozisyonlar';

    public $fillable = [
        'adi',
        'title',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'id' => 'integer',
        'adi' => 'string',
        'title' => 'string',
    ];


    protected $rules = [
        'adi' => 'nullable|string|max:10',
        'title' => 'nullable|string|max:100',
    ];

    protected $relationships = [ ];
}
