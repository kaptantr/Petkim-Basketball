<?php

namespace App\Models;

use Eloquent as Model;

class Lig extends Model
{
    public $timestamps = false;

    public $table = 'ligler';

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
