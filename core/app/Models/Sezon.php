<?php

namespace App\Models;

use Eloquent as Model;

class Sezon extends Model
{
    public $timestamps = false;

    public $table = 'sezonlar';

    public $fillable = [
        'adi',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'id' => 'integer',
        'adi' => 'string',
    ];


    public $rules = [
        'adi' => 'nullable|string|max:100',
    ];

    protected $relationships = [ ];
}
