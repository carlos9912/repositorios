<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


/**
 *
 * @tutorial Working Class
 * @author Eminson Mendoza ~~ ;
 * @since {28/05/2018}
 */

class Banco extends Model
{
    /**
     *
     * @var string
     */
    protected $table = 'banco_datos';
    /**
     *
     * @var string
     */

    protected $primaryKey = 'codbanco';



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
        'nombre',// character varying(125),
        'descripcion'
    ];



    /**
     *
     * @tutorial Method Description: obtiene el menu padre
     * @author Bayron Tarazona ~ bayronthz@gmail.com
     * @since {11/10/2018}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function Movimiento()
    {
        return $this->belongsTo('App\Models\Movimiento', 'cod_Movimiento');
    }

    public function curso()
    {
        return $this->belongsTo('App\Models\Curso', 'cod_curso');
    }

    public function sucursales()
    {
        return $this->belongsToMany('\App\Models\Sucursal', 'rel_sucursal_movimiento', 'codmovimiento', 'codsucursal')->withPivot('*');
    }

    public function campos()
    {

        return $this->hasMany('App\Models\ConvenioCampos', 'codconvenio');

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



