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

class Consignaciones extends Model
{
    /**
     *
     * @var string
     */
    protected $table = 'consignacion';
    /**
     *
     * @var string
     */

    protected $primaryKey = 'codconsignacion';



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
        'codconvenio',// integer,
        'codtarifa',// integer,
        'tipo_cuenta',// smallint,
        'numero_cuenta',// varchar (150),
        'numero_referencia',// varchar (150),
        'numero_convenio',// varchar (150),
        'identificacion_envia',// varchar (150),
        'telefono_envia',// varchar (150),
        'nombre_envia',// varchar (350),
        'identificacion_recibe',// varchar (150),
        'telefono_recibe',// varchar (150),
        'nombre_recibe',// varchar (350),
        'valor',// numeric,
        'valor_recibido',// numeric,
        'valor_tarifa',// numeric,
        'fecha',// timestamp without time zone,
        'estado',// smallint,
        'estado_pago',// smallint, 
        'fecha_pago',// timestamp without time zone,
        'codsucursal',// integer,
        'codempleado',// integer,
        'sms',// text,
        'observaciones',
        'codusuariopaga',
        'soporte'
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



