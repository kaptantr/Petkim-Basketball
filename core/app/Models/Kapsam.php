<?php

namespace App\Models;

use Eloquent as Model;

class Kapsam extends Model
{
    public $timestamps = false;

    public $table = 'kapsamlar';

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
