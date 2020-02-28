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

class Acceso extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'accesos';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'id_acceso';



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

        'id_usuario',// integer,

        'fecha_ingreso',// date NOT NULL default now(),

        'hora_ingreso',// time without time zone NOT NULL default now(),

        'direccion_ip',// character varying(50),

        'navegador'// text,

    ];



    

    /**

     * @tutorial Method Description: Consulta en la base de datos los accesos para los filtros seleccionados

     * ;

     * @since {4 sep. 2018}

     * @param unknown $idUsuario

     * @param unknown $rangoFechas

     * @return unknown

     */

    public static function listaAccesos($idUsuario = NULL, $rangoFechas = NULL){

        $listAccesos = DB::table('accesos AS acs')

        ->join('usuarios AS usu', 'usu.id_usuario', '=', 'acs.id_usuario')

        ->join('personas AS per', 'per.id_persona', '=', 'usu.id_persona');

        if (! blank($idUsuario)) {

            $listAccesos->where('acs.id_usuario', $idUsuario);

        }

        $fechasFormateada= [];

        if (!blank($rangoFechas)) {

            $fechas=explode(" - ", $rangoFechas);

            

            foreach ($fechas as $fecha) {

                $dateC = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y/m/d');

                $fechasFormateada[]= $dateC;

            }

        }else{

            $fechasFormateada[]= Carbon::today();

            $fechasFormateada[]= Carbon::today();

        }

            $listAccesos->whereBetween('acs.fecha_ingreso', $fechasFormateada);

        

        return $listAccesos->select('acs.*', 'per.*')->get();

    }



    /**

     * @tutorial Method Description: Consulta en la base de datos los datos estadisticos de los accesos 

     * ;

     * @since {4 sep. 2018}

     * @return unknown

     */

    public static function datosGraficosAcceso(){

        $listAccesos = DB::table('accesos AS acs');

        return [

            $listAccesos

            ->count(),

            $listAccesos->where('fecha_ingreso','>',Carbon::today()->subMonths(1))->select(DB::raw('count(*) as total'))

            ->groupBy('fecha_ingreso')

            ->orderBy('fecha_ingreso','asc')

            ->get()

            ->toArray(),

            $listAccesos->where('fecha_ingreso','=',Carbon::today())

            ->groupBy('fecha_ingreso')

            ->orderBy('fecha_ingreso','asc')

            ->count()

            

        ];

    }

}

