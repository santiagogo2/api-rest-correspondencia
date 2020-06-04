<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClasification extends Model
{
    // Tabla de la base de datos SubredSur que se usará para los departamentos
    protected $table = 'User_clasification';

    public function Users(){
    	return $this->hasMany('App\AppUsers', 'user_clasification_id', 'id');
    }

    protected $fillable = [
        'name',
    ];
}
