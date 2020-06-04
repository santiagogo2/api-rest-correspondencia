<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    // Tabla de base de datos que contiene la información de los radicados guardados en el servidor
    protected $table = 'Memorandum';

    public function AppUsers(){
    	return $this->belongsTo('App\AppUsers', 'app_users_id');
    }

    public function Dependence(){
    	return $this->belongsTo('App\Dependence', 'dependence_id');
    }

    public function Documents(){
    	return $this->belongsTo('App\Documents', 'documents_id');
    }

    public function Attached(){
        return $this->belongsTo('App\Attached', 'attached_id');
    }

    protected $fillable = [
        'app_users_id', 'dependence_id',
        'document_type_id', 'documents_id', 'subject', 'number_folios',
        'attached_id',
    ];
}
