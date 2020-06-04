<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUsers extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los departamentos
    protected $table = 'App_users';

    public function UserClasification(){
    	return $this->belongsTo('App\UserClasification', 'user_clasification_id');
    }

    public function Dependence(){
        return $this->belongsTo('App\Dependence', 'dependence_id');
    }

    public function Country(){
    	return $this->belongsTo('App\Country', 'country_id');
    }

    public function Department(){
    	return $this->belongsTo('App\Department', 'department_id');
    }

    public function Municipality(){
    	return $this->belongsTo('App\Municipality', 'municipality_id');
    }

    protected $fillable = [
        'user_clasification_id', 'dependence_id', 'name', 'surname', 'second_surname', 'phone', 'address', 'mail',
    ];
}
