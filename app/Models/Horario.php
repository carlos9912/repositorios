<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Cxeducativa\Autorizacion\Traits\AutorizacionTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Enums\ESiNo;
use App\Enums\EPerfiles;
use App\Enums\ECargoFederacion;
use App\Enums\ETipoNotificacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use App\Notifications\Notificacion;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 11/03/2018
 */
class Horario extends Authenticatable
{
    use Notifiable, AutorizacionTrait;

    /**
     *
     * @var string
     */
    protected $table = 'sucursal_horario';

    /**
     *
     * @var string
     */
    protected $primaryKey = 'codhorario';

    /**
     *
     * @tutorial The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'codsucursal',// integer,
        'horario_m_lv',// text,
        'horario_t_lv',// text,
        'horario_c_lv',// text,
        'jornada_continua_lv',// smallint,
        'horario_m_s',// text,
        'horario_t_s',// text,
        'horario_c_s',// text,
        'jornada_continua_s',// smallint,
        'horario_m_d',// text,
        'horario_t_d',// text,
        'horario_c_d',// text,
        'jornada_continua_d',// smallint,
    ];

    
    /**
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     *
     * @tutorial Method Description: retorna la persona relacionada con el usuario
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {24/03/2018}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function persona()
    {
        return $this->belongsTo('App\Models\Persona', 'id_persona');
    }

    /**
     *
     * @tutorial Method Description: retorna los perfiles que contiene el usuario
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {19/03/2018}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perfiles()
    {
        return $this->belongsToMany('App\Models\Perfil', 'perfil_usuario', 'id_usuario', 'id_perfil');
    }
    public function empleados()
    {
        return $this->belongsToMany('App\Models\Empleado', 'rel_empleado_sucursal', 'codsucursal', 'codempleado')->withPivot('*');
    }
    public function horarios()
    {
        return $this->hasMany('App\Models\Horario', 'codsucursal');
    }



    

    /**
     *
     * @tutorial Method Description: retorna los perfiles que contiene el usuario
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {19/03/2018}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permisos()
    {
        return $this->belongsToMany('App\Models\Permiso', 'permiso_usuario', 'id_usuario', 'id_permiso');
    }

    public function nombreCompleto(){
        return $this->nombres.' '.$this->apellidos;
    }

    public function movimientos()
    {
        return $this->hasMany('App\Models\Movimiento', 'codusuario');
    }

    public function ventas()
    {
        return $this->hasMany('App\Models\Venta', 'cod_punto');
    }

    /**
     *
     * @tutorial Method Description: genera la contrasenia aleatoria del usuario, por defecto de cuatro digitos
     * @author Bayron Tarazona ~~ conexioneducativa04@gmail.com
     * @since {11/06/2018}
     * @param integer $longitud            
     * @return Ambigous <string, number>
     */
    public static function generaPassword($longitud = 8)
    {
        $password = '';
        for ($i = 0; $i < $longitud; $i++) {
            $password .= mt_rand(0, 9);
        }
        return $password;
    }

    /**
     * @tutorial Method Description: Consulta los usuarios de los perfiles a notificar 
     * ;
     * @since {7 sep. 2018}
     * @param array $idPerfiles
     * @param integer $idBuscar
     * @return NULL[]
     */
    public static function idUsuariosNotificacion($idPerfiles = [], $idBuscar = null)
    {
        $listCorreos = [];
        $listUsuariosNotificar = [];
        //Consulta los usuarios pertenecientes a conaced
        if (\Illuminate\Support\Arr::exists($idPerfiles, EPerfiles::index(EPerfiles::CONACED)->getId())) {
            $listUsuariosConaced = DB::table('conaced AS con')
                ->join('conaced_persona AS con_p', 'con_p.id_conaced', '=', 'con.id_conaced')
                ->join('personas AS per', 'per.id_persona', '=', 'con_p.id_persona')
                ->join('usuarios AS usu', 'usu.id_persona', '=', 'per.id_persona')
                ->where('con_p.activo', '=', ESiNo::index(ESiNo::Si)->getId())
                ->select('usu.id_usuario', 'per.email')
                ->get();

            foreach ($listUsuariosConaced as $usuario) {
                $usuarioDB = Usuario::find($usuario->id_usuario);
                $usuarioDB->email = explode(";", $usuario->email);
                $listUsuariosNotificar[$usuario->id_usuario] = $usuarioDB;

            }
        }
        
        //Consulta los usuarios pertenecientes a las federaciones ya sea de una o de todas las federaciones
        if (\Illuminate\Support\Arr::exists($idPerfiles, EPerfiles::index(EPerfiles::FEDERACION)->getId())) {
            $listUsuariosFederacion = DB::table('federaciones AS fed')
                ->join('federacion_persona AS fed_p', 'fed_p.id_federacion', '=', 'fed.id_federacion')
                ->join('personas AS per', 'per.id_persona', '=', 'fed_p.id_persona')
                ->leftjoin('usuarios AS usu', 'usu.id_persona', '=', 'per.id_persona')
                ->where('fed_p.activo', '=', ESiNo::index(ESiNo::Si)->getId());
            if (!blank($idBuscar)) {
                $listUsuariosFederacion = $listUsuariosFederacion->where('fed.id_federacion', '=', $idBuscar);
            }
            $listUsuariosFederacion = $listUsuariosFederacion->select('usu.id_usuario', 'per.email', 'fed.email AS email_federacion')
                ->get();
            $usuarioDB = new Usuario();
            $emails = [];
            foreach ($listUsuariosFederacion as $usuario) {
                if (!blank($usuario->id_usuario)) {
                    $usuarioDB = Usuario::find($usuario->id_usuario);
                    if (!blank($usuario->email_federacion)) {
                        $emails = array_merge($emails, explode(";", $usuario->email_federacion));
                    }
                }
                if (!blank($usuario->email)) {
                    $emails = array_merge($emails, explode(";", $usuario->email));
                }

            }
            $usuarioDB->email = $emails;
            $listUsuariosNotificar[$usuarioDB->id_usuario] = $usuarioDB;
        }
        //Consulta los usuarios pertenecientes a las comunidades ya sea de una o de todas las comunidades
        if (\Illuminate\Support\Arr::exists($idPerfiles, EPerfiles::index(EPerfiles::COMUNIDAD)->getId())) {
            $listUsuariosComunidad = DB::table('comunidades AS com')
                ->join('comunidad_persona AS com_p', 'com_p.id_comunidad', '=', 'com.id_comunidad')
                ->join('personas AS per', 'per.id_persona', '=', 'col_m.id_persona')
                ->leftjoin('usuarios AS usu', 'usu.id_persona', '=', 'per.id_persona')
                ->where('col_m.activo', '=', ESiNo::index(ESiNo::Si)->getId());
            if (!blank($idBuscar)) {
                $listUsuariosComunidad = $listUsuariosComunidad->where('com.id_comunidad', '=', $idBuscar);
            }
            $listUsuariosComunidad = $listUsuariosComunidad->select('usu.id_usuario', 'per.email', 'com.email AS email_comunidad')
                ->get();
            $usuarioDB = new Usuario();
            $emails = [];
            foreach ($listUsuariosComunidad as $usuario) {
                if (!blank($usuario->id_usuario)) {
                    $usuarioDB = Usuario::find($usuario->id_usuario);
                    if (!blank($usuario->email_comunidad)) {
                        $emails = array_merge($emails, explode(";", $usuario->email_comunidad));
                    }
                }
                if (!blank($usuario->email)) {
                    $emails = array_merge($emails, explode(";", $usuario->email));
                }
            }
            $usuarioDB->email = $emails;
            $listUsuariosNotificar[$usuarioDB->id_usuario] = $usuarioDB;

        }
        //Consulta los usuarios pertenecientes a un colegio ya sea de uno o de todos los colegios
        if (\Illuminate\Support\Arr::exists($idPerfiles, EPerfiles::index(EPerfiles::COLEGIO)->getId())) {
            $listUsuariosColegio = DB::table('colegios AS col')
                ->join('colegio_persona AS col_p', 'col_p.id_colegio', '=', 'col.id_colegio')
                ->join('personas AS per', 'per.id_persona', '=', 'col_p.id_persona')
                ->leftjoin('usuarios AS usu', 'usu.id_persona', '=', 'per.id_persona')
                ->where('col_p.activo', '=', ESiNo::index(ESiNo::Si)->getId());
            if (!blank($idBuscar)) {
                $listUsuariosColegio = $listUsuariosColegio->where('col.id_colegio', '=', $idBuscar);
            }
            $listUsuariosColegio = $listUsuariosColegio->select('usu.id_usuario', 'per.email', 'col.email AS email_colegio')
                ->get();
            $usuarioDB = new Usuario();
            $emails = [];
            foreach ($listUsuariosColegio as $usuario) {
                if (!blank($usuario->id_usuario)) {
                    $usuarioDB = Usuario::find($usuario->id_usuario);
                    if (!blank($usuario->email_colegio)) {
                        $emails = array_merge($emails, explode(";", $usuario->email_colegio));
                    }
                }
                if (!blank($usuario->email)) {
                    $emails = array_merge($emails, explode(";", $usuario->email));
                }

            }
            $usuarioDB->email = $emails;
            $listUsuariosNotificar[$usuarioDB->id_usuario] = $usuarioDB;

        }

        return $listUsuariosNotificar;
    }

    /**
     * @tutorial Method Description: envia las notificaciones para los usuarios especificados, ademas construye el cuerpo de la notificacion, el origen y los datos a buscar en caso de que haya que seleccionar una determinada entidad
     * ;
     * @since {7 sep. 2018}
     * @param smallint $idTipoNotificacion
     * @param string $mensaje
     * @param integer $datosOrigen
     * @param integer $datosBusqueda
     */
    public static function enviarNotificacion($idTipoNotificacion, $mensaje, $datosOrigen = null, $datosBusqueda = null)
    {
        $opcionesNotificacion = ETipoNotificacion::result($idTipoNotificacion)->getAssistant();
        $perfilesNotificacion = ETipoNotificacion::result($idTipoNotificacion)->getAuxiliar();
        $opcionesNotificacion['contenido'] = $mensaje;
        $opcionesNotificacion[$opcionesNotificacion['origen']] = $datosOrigen;

        $listUsuarios = Usuario::idUsuariosNotificacion($perfilesNotificacion, $datosBusqueda);
        \Notification::send($listUsuarios, new notificacion($opcionesNotificacion));
    }



}
