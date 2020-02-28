<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;



/**

 *

 * @tutorial Working Class

 * ;

 * @since {28/05/2018}

 */

class Pagina extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'paginas';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'id_pagina';



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

        'id_pagina',// serial NOT NULL,

        'url_target',// character varying(255), -- Target del menu al que se asocia la pagina

        'titulo',// character varying(255), -- Titulo que ingresa el usuario para reconocer la pagina creada

        'pagina_editor',// text, -- Html que contiene las etiquetas de la pagina para poder editarla en cualquier momento

        'pagina_mostrar',// text, -- Html limpio para incrustarse en el contenedor principal

        'img_background',// character varying(255), -- Imagen de fondo

        'fecha_creacion',// timestamp without time zone, -- Fecha en que se crea la pagina

        'fecha_modificacion',// timestamp without time zone, -- Fecha en que se realiza modificacion de la pagina

        'estado',// smallint, -- EEstadoPagina (1: Guardada, 2: Publicada, 3: Suspendida, 4: Archivada)

        'id_usuario_registra',// integer, -- Id usuario que registra la pagina

        'id_menu',// integer,

        'principal',// smallint,

        'activo',// smallint,

        'id_usuario_modifica',// integer, -- Id usuario quien modifica la pagina

    ];





    /**

     *

     * @tutorial Method Description: obtiene el menu padre

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    public function menu()

    {

        return $this->belongsTo('App\Models\Menu', 'id_menu');

    }



    /**

     *

     * @tutorial Method Description: Consulta la informacion de las paginas y sus menus correspondientes con los filtros especificados

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {02/10/2018}

     */

    public static function listadoPagina($idMenu = NULL, $rangoFechas = NULL, $esPrincipal=NULL){

        $paginas = DB::table('paginas AS pag')

        ->join('menus AS men', 'men.id_menu', '=', 'pag.id_menu');

        if(!blank($idMenu)){

            $paginas = $paginas->where('pag.id_menu','=',$idMenu);

        }

        $contador=0;

        if (!blank($rangoFechas)) {

            $fechas = explode(" - ", $rangoFechas);

            foreach ($fechas as $fecha) {

                $dateC = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y/m/d');

                if($contador==0){

                    $fechasFormateada[] = $dateC.' 00:00:00';

                    $contador++;

                }else{

                    $fechasFormateada[] = $dateC.' 11:59:59';

                }

            }



            $paginas = $paginas->whereBetween('fecha_creacion', $fechasFormateada);

        }

        if(!blank($esPrincipal)){

            $paginas = $paginas->where('pag.principal','=',$esPrincipal);

        }

        $paginas = $paginas->where('pag.id_pagina','>',0);

        return $paginas->select('men.color', 'men.imagen', 'men.class_icon','men.nombre AS nombremenu','pag.*')->get();

    }



}

