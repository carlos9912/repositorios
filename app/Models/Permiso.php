<?php

namespace App\Models;



use Cxeducativa\Autorizacion\Models\Permiso as PermisoAuth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;



/**

 *

 * @tutorial Working Class

 * @author Bayron Tarazona ~bayronthz@gmail.com

 * @since 12/05/2018

 */

class Permiso extends PermisoAuth

{



    /**

     *

     * @var string

     */

    protected $table = 'permisos';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'id_permiso';



    /**

     *

     * @var string

     */

    const CREATED_AT = 'fecha_registro';



    /**
;
     *

     * @var string

     */

    const UPDATED_AT = 'fecha_modificacion';



    /**

     *

     * @tutorial Method Description: The attributes that are fillable via mass assignment.

     * @var unknown

     */

    protected $fillable = [

        'descripcion',

        'nombre',

        'url'

    ];



    /**

     *

     * (non-PHPdoc)

     *

     * @tutorial Method Description: Permissions can belong to many roles.

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {13/05/2018}

     * @return Ambigous <\Illuminate\Database\Eloquent\Relations\BelongsToMany, \Illuminate\Database\Eloquent\Relations\Concerns\$this, \Illuminate\Database\Eloquent\Relations\BelongsToMany>

     * @see \Caffeinated\Shinobi\Models\Permission::roles()

     */

    public function perfiles()

    {

        return $this->belongsToMany('\App\Models\Perfil', 'perfil_permiso', 'id_permiso', 'id_perfil')->withTimestamps();

    }



    /**

     *

     * @tutorial Method Description: retorna la lista de los permisos

     * ;

     * @since {28/06/2018}

     * @return array

     */

    public static function listPermisos()

    {

        $query = DB::table('permisos AS per')->join('menus AS men', 'men.id_menu', '=', 'per.id_menu');

        return $query->select('per.*', 'men.nombre AS nombreMenu')->get();

    }



    public static function permisosIndices(){

        return trans("menu");

    }

    

}

