<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    // Tabla de base de datos que contiene la información de los documentos guardados en el servidor

    protected $table = 'Documents';

    protected $fillable = [
    	'name', 'document_name', 'created_at'
    ];
}
