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

class Inventario extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'inventario';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'codinventario';



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

        'codproducto',// integer,

        'pin',// text,

        'estado',// smallint,

        'fecha_ingreso',// timestamp without time zone,

        'fecha_venta',// timestamp without time zone,

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

    public function ventas()

    {

        return $this->hasMany('App\Models\Venta', 'codinventario');

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

    public function producto()

    {

        return $this->belongsTo('App\Models\Producto', 'codproducto');

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

