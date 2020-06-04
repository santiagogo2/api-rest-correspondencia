<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los municipios
    protected $table = 'Municipality';

    public function Departments(){
    	return $this->belongsTo('App\Department', 'deparment_id');
    }

    protected $fillable = [
        'department_id', 'name',
    ];
}
