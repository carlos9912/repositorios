<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Parametro;
use App\Models\Permiso;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Enums\EActivo;
use App\Enums\ESiNo;
use App\Enums\EEstadoPagina;
use App\Models\Perfil;
use App\Models\Pagina;
use App\Models\Menu;
use App\Models\Movimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use Mail;


class EmpleadoController extends Controller
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
     * @tutorial Method Description: carga la vista de crear pagina
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/03/2018}
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */

    public function index(Request $request)
    {
        if(!blank(\Auth::user()->codempleado)){
            return redirect()->route('errors.empleado-403');
        }
        $listAsignaturas = [];
        if(!blank($request->codcurso)){
            $listAsignaturas = DB::table('asignatura AS a')->leftJoin('rel_asignaturadocente AS rel_a','rel_a.codasignatura','=','a.codasignatura')->where('curso','=',$request->codcurso)->orderBy('curso')->select('a.*','rel_a.coddocente')->get();
            $listDocentes = [];
            $listReturn = [];
            foreach($listAsignaturas as $asignatura){
                $listDocentes[$asignatura->codasignatura][] = $asignatura->coddocente;
                $asignatura->coddocente = $listDocentes[$asignatura->codasignatura];
                $listReturn[$asignatura->codasignatura] = $asignatura;
            } 
            $listAsignaturas = $listReturn;
        }
        return view('empleado.index');
    }



    public function abrirFormulario(Request $request){
        if(blank($request->codempleado)){
            $empleado= new Empleado();
        }else{
            $empleado = Empleado::find($request->codempleado);
        }
        return response()->json([
            'formulario' => view('empleado.partials.form')->with([
                'empleado' => $empleado,
            ])->render()
        ]);
    }


    

    public function resetearPassword(Request $request){
        $empleado = Empleado::find($request->codempleado);
        $usuario = ($empleado->usuario()->first());
        $password = rand(00000000,99999999);
        $password = $empleado->identificacion;
            $usuario->update([
                "username" => $empleado->identificacion,
                "password" => bcrypt($password),
            ]);
            $data = [
                "email" => $empleado->email,
                "asunto" => "RECUPERACIÓN DE CONTRASEÑA",
                "password" => $password,
                "nombre" => $empleado->nombreCompleto()
            ];
            //Mail::send('configuracion.password', $data, function($message) use ($data){
            //    $message->from('afainversiones@gmail.com','AFAInversiones - Giros');
            //    $message->to($data['email']);
            //    $message->subject($data['asunto']);
            //});
        return response()->json([
            'formulario' => $empleado
        ]);
    }
    

    public function eliminar(Request $request){
        DB::table('rel_empleado_sucursal')->where('codempleado', '=', $request->codempleado)->delete();
        DB::table('usuarios')->where('codempleado', '=', $request->codempleado)->delete();
        $empleado = Empleado::destroy($request->codempleado);
        return response()->json([
            'formulario' => $empleado
        ]);
    }

    public function bloqueo(Request $request){
        DB::table('usuarios')->where('codempleado', '=', $request->codempleado)->update(['bloqueo'=>$request->bloqueo]);
        $empleado = Empleado::find($request->codempleado);
        $empleado->update(['bloqueo'=>$request->bloqueo]);
        return response()->json([
            'formulario' => $empleado
        ]);
    }



    public function registrarConsignacion(Request $request){
        $file = \Illuminate\Support\Facades\Input::file('soporte');
        $nombre = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = 'soportes';
        $mensaje = "Ocurrió un error a la hora de registrar la consignación";
        $error=1;
        if($file->move($destinationPath,$nombre)){
            \Auth::user()->movimientos()->save(new Movimiento([
                'valor' => $request->valor,
                'soporte' => $nombre,
                'fecha_registro' => Carbon::now(),
                'estado' => 2,
                'banco' => $request->banco
            ]));
            $mensaje = "La consignación se registro con éxito";
            $error=0;
        }
        return redirect()->route('empleados.index', [
            'm' => base64_encode($mensaje),
            'd' => $error
        ]);
        exit();
        dd($file,$request->all());
    }



    /**
     *
     * @tutorial Method Description: carga la vista de crear pagina
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/03/2018}
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function crearEmpleado(Request $request)
    {
        $dataempleado=$request->all();
        unset($dataempleado["_token"]);
        unset($dataempleado["celular_confirmar"]);
        $dataempleado['fecha_nacimiento'] = Carbon::parse( $dataempleado['fecha_nacimiento'])->format('Y/m/d');
        $dataempleado['fecha_expedicion'] = Carbon::parse( $dataempleado['fecha_expedicion'])->format('Y/m/d');
        if(blank($request->codempleado)){
            $empleado = new Empleado($dataempleado);
            $empleado->save();
            $password = rand(00000000,99999999);
            $password = $empleado->identificacion;
            $usuario = new Usuario([
                "username" => $empleado->identificacion,
                "password" => bcrypt($password),
                "codempleado" => ($empleado->codempleado),
            ]);
            $usuario->save();
            $data = [
                "email" => $empleado->email,
                "asunto" => "Bienvenido a AFAINVERSIONES",
                "password" => $password,
                "nombre" => $empleado->nombreCompleto()
            ];
                //Mail::send('configuracion.mail', $data, function($message) use ($data){
                //    $message->from('afainversiones@gmail.com','AFAInversiones - Giros');
                //    $message->to($data['email']);
                //    $message->subject($data['asunto']);
                //});
            $request->mensaje = "Asignatura guardada con éxito";
        }else{
            $empleado = Empleado::find($request->codempleado);
            $empleado->update($dataempleado);
        }
        return redirect()->route('empleado.index', [
            'mensaje' => "Operación realizada con éxito",
        ]);
        exit();
        return view('asignaturas.index',[
            'mensaje' => "Asignatura guardada con éxito",
            'listEstudiantes' => $listAsignaturas,
            'listDocentes' => DB::table('docente')->pluck('nombres', 'coddocente'),
            'listGrados' => DB::table('curso')->orderBy('orden')->pluck('nombrecurso', 'codcurso'),
            'menus' => Menu::listMenusHijos(),
            'codcurso' => $request->codcurso
        ]);
    }





}

