<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPermiso extends Model
{
    /**
     *
     * @var string
     */
    protected $table = 'perfil_permiso';
    
    /**
     *
     * @var string
     */
    protected $primaryKey = null;
    
    /**
     *
     * @var string
     */
    public $incrementing = false;
    
    /**
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     *
     * @tutorial The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id_perfil',// integer NOT NULL, -- Id del perfil
        'id_permiso',// integer NOT NULL, -- Id del permiso
        'fecha_registro',// date NOT NULL DEFAULT now(), -- Fecha del registro
        'fecha_modificacion',// date, -- Fecha de modificacion
        'id_menu'// integer NOT NULL, -- Id del menu
    ];
}
