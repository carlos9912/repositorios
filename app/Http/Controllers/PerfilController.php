<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

use App\Models\Permiso;

use App\Enums\EAcceso;

use App\Enums\ESiNo;

use App\Models\Perfil;

use App\Models\Menu;

use App\Models\PerfilPermiso;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



/**

 *

 * @tutorial Working Class

 * @author Bayron Tarazona ~bayronthz@gmail.com

 * @since 22/04/2018

 */

class PerfilController extends Controller

{



    /**

     *

     * @tutorial Method Description: constructor class

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {22/04/2018}

     */

    public function __construct()

    {

        $this->middleware('auth');

    }



    /**

     *

     * @tutorial Method Description: carga los menus creados

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {22/04/2018}

     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>

     */

    public function index()

    {

        $list = Perfil::orderBy("id_perfil")->get();

        return view('perfiles.index', compact('list'));

    }



    /**

     *

     * @tutorial Method Description: Muestra el formulario para crear un nuevo perfil

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {25/04/2018}

     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>

     */

    public function create()

    {

        $perfil = null;

        $listPermisos = [];

        $menus = Menu::orderby('id_menu_padre', 'orden')->get();

        $permisos = Permiso::orderby('id_permiso')->get();

        return view('perfiles.create', compact('perfil', 'menus', 'permisos', 'listPermisos'));

    }



    /**

     *

     * @tutorial Method Description: carga la vista para ver el detalle del menu

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {12/05/2018}

     * @param Perfil $perfil

     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>

     */

    public function show(Perfil $perfil)

    {

        return view('perfiles.show', compact('perfil'));

    }



    /**

     *

     * @tutorial Method Description: carga la vista para editar el perfil

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {22/04/2018}

     * @param Perfil $perfil

     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>

     */

    public function edit(Perfil $perfil)

    {

        $menus = menu::orderby('orden')->get();

        $permisos = Permiso::all();

        return view('perfiles.edit', compact('perfil', 'menus', 'permisos'));

    }



    /**

     *

     * @tutorial Method Description: valida y guarda el perfil

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {22/04/2018}

     * @return Ambigous <\Illuminate\Http\RedirectResponse, mixed, \Illuminate\Support\HigherOrderTapProxy>

     */

    public function store(Request $request)

    {;

        // valida los campos y obtiene los valores

        $validator = Validator::make($request->all(), [

            'nombre' => 'required|unique:perfiles|min:3|max:255'

        ]);

        

        // $validator->errors()

        if ($validator->fails()) {

            return response()->json([

                'errors' => $validator->errors(),

                'fail' => true

            ]);

        }

        

        $perfil = Perfil::create(["nombre"=>$request->get("nombre"),"fecha_registro"=>Carbon::today(),"estado" => $request->get("activo")]);

        return response()->json([

            'mensaje' => 'Perfil creado con exito!',

            'nombre' => $perfil->nombre,

            'id_perfil' => $perfil->id_perfil,

            'estado' => $perfil->estado

        ]);

    }



    /**

     *

     * @tutorial Method Description: valida y guarda los datos del perfil

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {28/04/2018}

     * @param Request $request

     * @param Perfil $perfil

     * @return Ambigous <\Illuminate\Http\$this, \Illuminate\Http\RedirectResponse>|Ambigous <\Illuminate\Http\RedirectResponse, mixed, \Illuminate\Support\HigherOrderTapProxy>

     */

    public function update(Request $request)

    {

        $perfil=Perfil::find($request->get('id'));

        $perfil->update(["estado"=>(($request->get('estado')== ESiNo::index(ESiNo::Si)->getId()) ? ESiNo::index(ESiNo::No)->getId() : ESiNo::index(ESiNo::Si)->getId())]);

        $claseAdd = ($perfil->estado == ESiNo::index(ESiNo::Si)->getId()) ? 'btn-outline-info' : 'btn-outline-danger' ;

        $claseRemover = ($perfil->estado == ESiNo::index(ESiNo::Si)->getId()) ? 'btn-outline-danger' : 'btn-outline-info' ;

        $icon = ($perfil->estado == ESiNo::index(ESiNo::Si)->getId()) ? 'ti-check-box' : 'ti-lock'  ;

        return response()->json([

            'message' => 'Perfil actualizado con exito',

            'remover' => $claseRemover,

            'agregar' => $claseAdd,

            'icon' => $icon,

            'estado' => $perfil->estado

        ]);

    }



    /**

     *

     * @tutorial Method Description: elimina el perfil y los registros asociados

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {22/04/2018}

     * @param Perfil $perfil

     * @return Ambigous <\Illuminate\Http\RedirectResponse, mixed, \Illuminate\Support\HigherOrderTapProxy>

     */

    function destroy(Perfil $perfil)

    {

        $correct = false;

        if (! blank($perfil->id_perfil)) {

            if (blank($perfil->permisos) && $perfil->especial != EAcceso::index(EAcceso::Si)->getId()) {

                $correct = $perfil->delete();

                $message = __('perfiles.delete_status', [

                    'nombre' => $perfil->nombre

                ]);

            } else {

                $message = trans('perfiles.delete_error', [

                    'nombre' => $perfil->nombre

                ]);

            }

        }

        return response()->json([

            'message' => $message,

            'correct' => $correct

        ]);

    }



    /**

     *

     * @tutorial Method Description: change state perfil

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {14/05/2018}

     * @param Perfil $perfil

     * @return \Illuminate\Http\JsonResponse

     */

    public function change_status(Perfil $perfil)

    {

        $result = false;

        if (! blank($perfil->id_perfil)) {

            $result = $perfil->update([

                'activated' => ! $perfil->activated

            ]);

        }

        return response()->json([

            'content' => $result

        ]);

    }



    /**

     * @tutorial Method Description: Guarda los permisos en los menus para el perfil seleccionado

     * ;

     * @since {17 sep. 2018}

     * @param Request $request

     * @return \Illuminate\Http\JsonResponse

     */

    public function sync(Request $request)

    {

        $perfil = Perfil::find($request->get("id_perfil"));

        $dataPerfiles = [];

        DB::table('perfil_permiso')->where('id_perfil', '=', $perfil->id_perfil)->delete();

        foreach ($request->get('permiso_chk') as $informacion) {

            $info = explode("-", $informacion);

            $dataPerfiles[] = [

                'id_perfil' => $perfil->id_perfil,

                'id_permiso' => $info[1], 

                'fecha_registro' => Carbon::today(), 

                'fecha_modificacion' => Carbon::today(),

                'id_menu' => $info[0]

            ];

            

        }

        DB::table('perfil_permiso')->insert($dataPerfiles);

        //$list = Perfil::all();

        return response()->json([

            'mensaje' => 'Perfil creado con exito!'

        ]);

    }

}