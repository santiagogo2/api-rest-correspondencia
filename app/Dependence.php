<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dependence extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los departamentos
    protected $table = 'Dependence';

    protected $fillable = [
        'code', 'name', 'email'
    ];
}
