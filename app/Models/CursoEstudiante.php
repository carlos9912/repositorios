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

class Estudiante extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'rel_curso_estudiante';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'codrelcursoestudiante';



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

        'codrelcursoestudiante',// serial NOT NULL,

        'codestudiante',// integer,

        'codcurso',// integer,

        'numero',// character varying(20),

        'colegio',// character varying(100),

        'observacion',// character varying(100),

        'estado',// numeric,

        'fecha',// timestamp without time zone,

    ];



    /**

     *

     * @tutorial Method Description: obtiene el menu padre

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    public function estudiante()

    {

        return $this->belongsTo('App\Models\Estudiante', 'cod_estudiante');

    }





    public function curso()

    {

        return $this->belongsTo('App\Models\Curso', 'cod_curso');

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

