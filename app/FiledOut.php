<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiledOut extends Model
{
    // Tabla de base de datos que contiene la información de los radicados guardados en el servidor
    protected $table = 'Filed_out';

    public function AppUsers(){
    	return $this->belongsTo('App\AppUsers', 'app_users_id');
    }

    public function FiledIn(){
        return $this->belongsTo('App\FiledIn', 'filed_in_id');
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
        'app_users_id', 'filed_in_id', 'dependence_id',
        'documents_id', 'document_date', 'affair',
        'number_folios', 'attached_id', 'number_folios',
        'reference', 'guide', 'desName',
        'desSurname', 'desEntity', 'desAddress'
    ];
}