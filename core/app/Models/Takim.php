<?php

namespace App\Models;

use Eloquent as Model;

class Takim extends Model
{
    public $timestamps = false;

    public $table = 'takimlar';

    public $fillable = [
        'adi',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'id' => 'integer',
        'adi' => 'string',
    ];


    protected $rules = [
        'adi' => 'nullable|string|max:100',
    ];

    protected $relationships = [ ];
}
