<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attached extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los departamentos
    protected $table = 'Attached';

    protected $fillable = [
        'name',
    ];
}
