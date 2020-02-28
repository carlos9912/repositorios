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

    protected $table = 'estudiante';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'codestudiante';



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

        'tipodocumento',// character varying(2),

        'documento',// character varying(100),

        'nombres',// character varying(100),

        'fechanacimiento',// date,

        'genero',// smallint,

        'direccion',// character varying(100),

        'email',// character varying(100),

        'img',// character varying(100),

        'estado',// smallint,

        'fecha_creacion',// timestamp without time zone DEFAULT now(),

        'apellidos',// character varying(100),

        'tiposangre',// character varying(10),

        'estrato',// numeric(2,0),

        'eps',// character varying(50),

        'lugarnacimiento',// character varying(100),

        'madre',// character varying(100),

        'ocupacionmadre',// character varying(100),

        'telefonomadre',// character varying(100),

        'celularmadre',// character varying(100),

        'padre',// character varying(100),

        'ocupacionpadre',// character varying(100),

        'telefonopadre',// character varying(100),

        'celularpadre',// character varying(100),

        'telefono',// character varying(100),

        'acudiente',// character varying(100),

        'telefonoacudiente',// character varying(100),

        'ocupacionacudiente',// character varying(100),

        'celularacudiente',// character varying(100),

        'emailacudiente',// character varying(100),

        'imgdoc',// character varying(100),

        'emailpadre',// character varying(100),

        'emailmadre',// character varying(100),

    ];



    public function nombreCompleto(){

        return $this->nombres.' '.$this->apellidos;

    }



    public function documento(){

        return $this->tipodocumento.' - '.$this->documento;

    }

    

    public function madre(){

        return 'Madre: '.$this->madre.'<br>Ocupación '.$this->ocupacionmadre.'<br>Teléfono '.$this->telefonomadre;

    }

    public function padre(){

        return 'Padre: '.$this->padre.'<br>Ocupación '.$this->ocupacionpadre.'<br>Teléfono '.$this->telefonopadre;

    }

    public function acudiente(){

        return 'Acudiente: '.$this->acudiente.'<br>Ocupación '.$this->ocupacionacudiente.'<br>Teléfono '.$this->telefonoacudiente;

    }



    public function cursos()

    {

        return $this->belongsToMany('\App\Models\Curso', 'rel_cursoestudiante', 'codestudiante', 'codcurso')->withPivot('*');

    }



    public function cursoActivo()

    {

        return $this->cursos()->where('rel_cursoestudiante.estado','=',1)->orderBy('codrelcursoestudiante')->first();

    }

    

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





    public function observaciones()

    {

        return $this->hasMany('App\Models\Observaciones', 'codestudiante');

    }



    /**

     *

     * @tutorial Method Description: Consulta la informacion de las paginas y sus menus correspondientes con los filtros especificados

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {02/10/2018}

     */

    public static function listadoEstudiante($idGrado = NULL, $idAnualidad = NULL){

        $paginas = DB::table('estudiante AS est')

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

