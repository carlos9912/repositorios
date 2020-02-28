<?php
namespace App\Models;

use Cxeducativa\Autorizacion\Models\Perfil as PerfilAutorizacion;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 12/05/2018
 */
class Perfil extends PerfilAutorizacion
{

    /**
     *
     * @var string
     */
    protected $table = 'perfiles';

    /**
     *
     * @var string
     */
    protected $primaryKey = 'id_perfil';

    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'descricion',
        'especial',
        'nombre',
        'url',
        'estado'
    ];

    /**
     *
     * @var string
     */
    const CREATED_AT = 'fecha_registro';

    /**
     *
     * @var string
     */
    const UPDATED_AT = 'fecha_modificacion';


    /**
     *
     * (non-PHPdoc)
     *
     * @tutorial Method Description: Roles can belong to many users.
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {13/05/2018}
     * @return Ambigous <\Illuminate\Database\Eloquent\Relations\BelongsToMany, \Illuminate\Database\Eloquent\Relations\Concerns\$this, \Illuminate\Database\Eloquent\Relations\BelongsToMany>
     * @see \Caffeinated\Shinobi\Models\Role::users()
     */
    public function usuarios()
    {
        return $this->belongsToMany('\App\Models\Usuario', 'perfil_usuario', 'id_perfil', 'id_usuario');
    }

    /**
     *
     * @tutorial Method Description: Users and Roles can have many permissions
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {13/05/2018}
     * @return Ambigous <\Illuminate\Database\Eloquent\Relations\BelongsToMany, \Illuminate\Database\Eloquent\Relations\Concerns\$this, \Illuminate\Database\Eloquent\Relations\BelongsToMany>
     */
    public function permisos()
    {
        return $this->belongsToMany('\App\Models\Permiso', 'perfil_permiso', 'id_perfil', 'id_permiso')->withPivot("*");
    }
    
    
}
