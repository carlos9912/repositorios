<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;

use App\Enums\ETipoUbicacion;

use Carbon\Carbon;



/**

 *

 * @tutorial Working Class

 * ;

 * @since {28/05/2018}

 */

class Parametro extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'parametros';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'id_parametro';



    /**

     *

     * @var boolean

     */

    public $timestamps = false;



    /**

     *

     * @var array

     */

    protected $fillable = [

        'id_parametro',// serial NOT NULL,

        'ancho_galeria',// numeric,

        'alto_galeria',// numeric,

        'boxed',// smallint,

        'nombre_principal',// character varying(255),

        'correo',// character varying(255),

        'telefono',// character varying(255),

        'lema',// character varying(255),

    ];

}

