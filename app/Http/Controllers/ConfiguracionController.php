<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Usuario;
use App\Models\Permiso;
use App\Models\Configuracion;
use App\Models\Menu;
use App\Enums\EActivo;
use App\Enums\ESiNo;
use App\Enums\EIcono;
use App\Models\Perfil;
use App\Models\Pagina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ConfiguracionController extends Controller
{



    /**
     *
     * @tutorial Method Description: constructor class
     * @author Bayron Tarazona ~ bayronthz@gmail.com
     * @since {02/10/2018}
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * @tutorial Method Description: Carga la pagina de administracion de configuracion
     * @author Bayron Tarazona ~ bayronthz@gmail.com
     * @since {02/10/2018}
     */
    public function index()
    {
        //Se consulta las configuraciones
        $configuracion = Configuracion::find(1);
        return view('configuracion.index',[
            'configuracion' => $configuracion
        ]);
    }

    /**
     *
     * @tutorial Method Description: Consulta la informacion de la configuracion
     * @author Bayron Tarazona ~ bayronthz@gmail.com
     * @since {02/10/2018}
     */
    public function consultarConfiguraciones(Request $request)
    {
        $configuracion = Configuracion::find(1);
        return response()->json([
            'configuracion' => $configuracion
        ]);
    }

}
