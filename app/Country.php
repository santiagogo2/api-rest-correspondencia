<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los paises
    protected $table = 'Country';

    public function Departments(){
    	return $this->hasMany('App\Department', 'country_id', 'id');
    }

    public function Municipalities(){
    	return $this->hasManyThrough(
    			'App\Municipality', 'App\Department',
    			'country_id', 'department_id', 'id');
    }

    protected $fillable = [
        'code', 'name',
    ];
}
