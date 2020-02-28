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

class Evidencia extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'logro';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'codlogro';



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

        'nombrelogro',// character varying(50),

        'descripcion',// character varying(500),

        'estado',// numeric,

    ];



    

    

    /**

     *

     * @tutorial Method Description: obtiene el menu padre

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    public function docente()

    {

        return $this->belongsToMany('\App\Models\Docente', 'rel_asignaturadocente', 'codasignatura', 'coddocente')->withPivot('*');

    }







    public static function listadoAsignatura($idGrado = NULL){

        $paginas = DB::table('asignatura AS est')

        ->join('rel_cursoestudiante AS rel', 'rel.codestudiante', '=', 'est.codestudiante')

        ->join('curso AS cur', 'rel.codcurso', '=', 'cur.codcurso');

        $idAnualidad = blank($idAnualidad) ? Carbon::today()->year : $idAnualidad;

        $paginas = $paginas->whereBetween('rel.fecha', [

            $idAnualidad.'-01-01 00:00:00',

            $idAnualidad.'-12-31 11:59:59'

        ]);

        if(!blank($idGrado)){

            $paginas = $paginas->where('rel.codcurso','=',$idGrado);

        }

        

        return $paginas->select('est.*', 'rel.*', 'cur.*', 'rel.estado AS estadoCurso')->get();

    }



}

