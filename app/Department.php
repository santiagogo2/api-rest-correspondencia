<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los departamentos
    protected $table = 'Department';

    public function Countries(){
    	return $this->belongsTo('App\Country', 'country_id');
    }

    public function Municipalities(){
    	return $this->hasMany('App\Municipality', 'department_id', 'id');
    }

    protected $fillable = [
        'country_id', 'code', 'name',
    ];
}
