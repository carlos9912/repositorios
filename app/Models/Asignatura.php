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

class Asignatura extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'asignatura';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'codasignatura';



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

        'nombreasignatura',// character varying(100),

        'codigo',// character varying(100),

        'estado',// numeric,

        'observacion',// character varying(100),

        'fecha_creacion',// timestamp without time zone DEFAULT now(),

        'curso',// integer,

        'intensidad',// character varying(10),

    ];



    /**

     *

     * @tutorial Method Description: obtiene el menu padre

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    public function evidenciasDetalle()

    {

        return $this->belongsToMany('App\Models\Evidencia', 'rel_asignaturalogro', 'codasignatura', 'codlogro')->withPivot('*');

    }



    public function evidencias()

    {

        return $this->belongsToMany('App\Models\Evidencia', 'rel_asignaturalogro', 'codasignatura', 'codlogro');

    }

    

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

