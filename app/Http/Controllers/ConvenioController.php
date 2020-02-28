<?php



namespace App\Http\Controllers;







use Curl;
use Excel;
use Carbon\Carbon;
use App\Support\Util;
use App\Models\Winred;
use App\Enums\ESiNo;
use App\Models\Menu;
use App\Models\Curso;
use App\Models\Pagos;
use App\Enums\EActivo;
use App\Models\Pagina;
use App\Models\Perfil;
use App\Models\Docente;
use App\Models\Usuario;
use App\Models\Convenio;
use App\Models\Sucursal;
use App\Models\Parametro;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\Movimiento;
use Illuminate\Support\Arr;
use App\Enums\EEstadoPagina;
use App\Models\Consignacion;
use Illuminate\Http\Request;
use App\Models\Consignaciones;
use App\Models\ConvenioCampos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ConvenioController extends Controller
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

    

    public function detalleConvenios(Request $request)
    {
        $convenio = Convenio::find($request->codconvenio);
        $campos = DB::table('banco_datos AS bd')
        ->join('rel_convenio_banco as r','r.codbanco','=','bd.codbanco')
        ->where('r.estado','=',1)
        ->where('bd.estado','=',1)
        ->where('codconvenio','=', $request->codconvenio)->orderBy('r.codrel')->get();

        $bancos = $convenio->campos;
        
        return response()->json([
            'formulario' => view('convenios.form')->with([
                'convenio' => $convenio,
                'campos' => $campos
            ])->render(),
            'convenios' => $convenio,
            'cantidad' => $campos
        ]);
    }

    public function buscarPaquete(Request $request)
    {
        $convenios = DB::table('recargas_paquetes AS a')->where('codpadre','=', $request->codpadre)->orderBy('valor')->get();
        $listConvenios = [];
        $listConveniosDetalle = [];
        $xhtml = '<option val="">SELECCIONE PAQUETE</option>';
        $xhtml2 = '<select id="seleccionadorPaquete" class="chosen-select" onchange="return paquete(this)" autodata-scrollbar="true">';
        $xhtml2.= $xhtml;

        if(!blank($convenios)){
            foreach ($convenios as $convenio) {
                $xhtml2.= '<option data-valor="'.$convenio->valor.'" data-value="'.$convenio->codigo.'" class="paquete-btn" >'."<b>".number_format($convenio->valor)."</b> ".$convenio->nombre." - VIGENCIA: ".$convenio->vigencia.'</b></option>';
                //$xhtml.= "<option data-valor='".$convenio->valor."' value='".$convenio->codigo."'>""</option>";
                $listConvenios[$convenio->codigo] = $convenio->nombre;
                $listConveniosDetalle[$convenio->codigo] = $convenio;
            }
        }else if($request->codpadre==21){
            foreach(Util::getMunicipios() as $key => $municipio){
                $xhtml2.= '<option data-valor="20500" data-value="'.$key.'" class=" paquete-btn" >'."<b>SNR ".$municipio." (".$key.')</b></option>';
                //$xhtml.= "<option data-valor='".$convenio->valor."' value='".$convenio->codigo."'>""</option>";
                $listConvenios[$key] = $municipio;
                $listConveniosDetalle[$key] = $municipio;
            }
        }
        $xhtml2.="</select></div>";
        return response()->json([
            'convenios' => $xhtml2,

            'cantidad' => count($listConvenios),
            'conveniosDetallados' => $listConveniosDetalle
        ]);
    }
    
    public function validarWPLAY(Request $request)
    {
        $api = new Winred();
        $request_date=date('YmdHis.000');
        //$api->pin('4',$request_date, $request->operador, '0');
        $transaccion = $api->sportBet('4',$request_date,'18',"$request->valor","$request->identificacion","$request->numero",false);
                       //$api->sportBet('4',$request_date,'18','1000','my CC','3165782288',false)."\n");
        $response = json_decode($transaccion, true);
        if($response==null){
            $response["result"]["success"] = false;
            $response["result"]["message"] = 'No fue posible comunicarse con el proveedor';
        }
        return response()->json([
            'informacion' => $response,
            'transaccion' => $transaccion,
            'success' => $response["result"]["success"]
        ]);
    }    
    public function validarSNR(Request $request)
    {
        $api = new Winred();
        $request_date=date('YmdHis.000');
        //$api->pin('4',$request_date, $request->operador, '0');
        //cambio
        $transaccion = $api->snr('4',$request_date,'21','0',"$request->matricula","$request->numero","$request->correo","$request->operador",false);
        $response = json_decode($transaccion, true);
        if($response==null){
            $response["result"]["success"] = false;
            $response["result"]["message"] = 'No fue posible comunicarse con el proveedor';
        }
        return response()->json([
            'informacion' => $response,
            'transaccion' => $transaccion,
            'success' => $response["result"]["success"]
        ]);
    }
    
    public function soatValidar(Request $request)
    {
        $request_date=date('YmdHis.000');
        $api = new Winred();

        $transaccion = $api->soat(3,$request_date,'25','0',$request->placa,$request->tipo_identificacion,$request->identificacion,$request->tipo_vehiculo,$request->celular,$request->correo,false);
        $response = json_decode($transaccion, true);
        if($response==null){
            $response["result"]["success"] = false;
            $response["result"]["data"] = '';
            $response["result"]["message"] = 'No fue posible comunicarse con el proveedor ';
        }else{
            if(!$response["result"]["success"]){
                $response["result"]["data"] = '';
                $response["result"]["message"] = 'Ocurrió un error al validar el SOAT de las placas '.$request->placa.'. Por favor intente en unos minutos';
            }
        }
        return response()->json([
            'informacion' => $response,
            //'data' => json_decode($response["result"]["data"], true),
            'transaccion' => $transaccion,
            'success' => $response["result"]["success"],
            'message' => $response["result"]["message"]
        ]);
        exit();
    }
    
    public function buscarConvenios(Request $request)
    {
        $convenios = DB::table('convenios AS a')->where('estado','=',1)->where('codcategoria','=', $request->codcategoria)->get();
        $listConvenios = [];
        $listConveniosDetalle = [];
        if($request->codcategoria==3 || $request->codcategoria==4){
            $xhtml = '<h5 class="text-green">Seleccione convenio</h5>';
            $xhtml.= '<div class="row">';
            if(!blank($convenios)){
                foreach ($convenios as $convenio) {
                    $xhtml.='<div class="col-sm-2 mb-4 p-0">
                                <a data-toggle="tooltip" data-placement="top" title="'.$convenio->nombre.'" onclick="return consultarConvenio('.$convenio->codconvenio.')" class="convenio-pick convenio-pick-'.$convenio->codconvenio.'">
                                    <img src="'.asset('img/convenios/'.$convenio->img).'" alt="" class="img-fluid img-thumbnail" style="    border-radius: 50%;">
                                </a>
                            </div>';
                    //$xhtml.= "<option value='".$convenio->codconvenio."'>".$convenio->nombre."</option>";
                    $listConvenios[$convenio->codconvenio] = $convenio->nombre;
                    $listConveniosDetalle[$convenio->codconvenio] = $convenio;
                }
            }
            $xhtml.= '</div>';
        }else{
            $xhtml = '<option value="">Seleccione convenio</option>';
            if(!blank($convenios)){
                foreach ($convenios as $convenio) {
                    $xhtml.= "<option value='".$convenio->codconvenio."'>".$convenio->nombre."</option>";
                    $listConvenios[$convenio->codconvenio] = $convenio->nombre;
                    $listConveniosDetalle[$convenio->codconvenio] = $convenio;
                }
            }
        }
        
        
        return response()->json([
            'convenios' => $xhtml,
            'cantidad' => count($listConvenios),
            'conveniosDetallados' => $listConveniosDetalle
        ]);
    }
    
    
    
    public function crearConvenio(Request $request)

    {
        $documento = \Illuminate\Support\Facades\Input::file('img');
        $dataConvenio=$request->all();
        unset($dataConvenio["_token"]);
        $dataConvenio['estado'] = 2;
        $mensaje = "Ocurrió un error a la hora de registrar la consignación";
        $error=1;
        //dd($request->all());
        if(blank($request->codconvenio)){
            $convenio = new Convenio($dataConvenio);
            $convenio->save();
            $request->mensaje = "Asignatura guardada con éxito";
        }else{
            $convenio = Convenio::find($request->codconvenio);
            $convenio->update($dataConvenio);
        }
        if(!blank($documento)){
            $nombre = $convenio->codconvenio.'-'.time().'.'.$documento->getClientOriginalExtension();
            $destinationPath = 'img/convenios/';
            
            if($documento->move($destinationPath,$nombre)){
                $convenio->update([
                    'img' => $nombre
                ]);
                echo "ok<br>";
            }
        }
        if(!blank($request->codbanco)){
            $convenio->campos()->save(new ConvenioCampos([
                'codbanco' => $request->codbanco,
                'estado' => blank($request->estado_campo) ? 2 : 1,
                'obligatorio' => blank($request->obligatorio_campo) ? 2 : 1,
                'numerico' => blank($request->numerico_campo) ? 2 : 1,
                'cantidad' => $request->longitud_campo,
            ]));
            
        }
        
        
        return redirect()->route('convenios.index', [
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

    public function abrirFormulario(Request $request)
    {
        if(blank($request->codconvenio)){
            $convenio= new Convenio();
        }else{
            $convenio = Convenio::find($request->codconvenio);
        }
        return response()->json([
            'formulario' => view('convenios.partials.form-editar')->with([
                'convenio' => $convenio,
            ])->render()
        ]);
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
        
        return view('convenios.index',[
            'mensaje' => "",
            'menus' => Menu::listMenusHijos()
        ]);
    }
    

    public function pagos(Request $request)
    {
        
        $sucursal = Sucursal::first();
        if(!blank(\Auth::user()->codempleado)&&\Auth::user()->perfil!=2&&$sucursal->codsucursal!=session('sucursal')->codsucursal){
        
            return redirect()->route('errors.empleado-403');
        }
        $documentos = $sucursal->documentos;
        $listDocumentos = [];
        foreach($documentos as $documento){
            $listDocumentos[$documento->tipo] = $documento;
        }
        $listMovimientos = DB::table('rel_sucursal_movimiento AS r')->leftjoin('tarifas AS t', function ($join) {
            $join->on('t.codtarifa', '=', 'r.codtarifa')
                ->whereNotNull('r.codmovimiento');
            })->join('sucursales AS s','s.codsucursal','=','r.codsucursal')->where('r.codsucursal','=',$sucursal->codsucursal)->select('r.*','t.*','s.nombres AS nombreSucursal', 's.ubicacion', 'r.codtarifa', 'r.estado')->get();
        
            return view('convenios.pagos',[
            'sucursal' => $sucursal,
            'fecha' => $request->rango_fecha,
            'documentos' => $listDocumentos,
            'listMovimientosSucursal' => $listMovimientos,
            'listMovimientos' => $listMovimientos,
            'mensaje' => "",


        ]);
       
    }
    
    public function otros(Request $request)
    {
        $sucursal = Sucursal::first();
        if(!blank(\Auth::user()->codempleado)&&\Auth::user()->perfil!=2&&$sucursal->codsucursal!=session('sucursal')->codsucursal){
            return redirect()->route('errors.empleado-403');
        }
        $documentos = $sucursal->documentos;
        $listDocumentos = [];
        foreach($documentos as $documento){
            $listDocumentos[$documento->tipo] = $documento;
        }
        $listMovimientos = DB::table('rel_sucursal_movimiento AS r')->leftjoin('tarifas AS t', function ($join) {
            $join->on('t.codtarifa', '=', 'r.codtarifa')
                ->whereNotNull('r.codmovimiento');
            })->join('sucursales AS s','s.codsucursal','=','r.codsucursal')->where('r.codsucursal','=',$sucursal->codsucursal)->select('r.*','t.*','s.nombres AS nombreSucursal', 's.ubicacion', 'r.codtarifa', 'r.estado')->get();
        
            return view('convenios.otros',[
            'sucursal' => $sucursal,
            'fecha' => $request->rango_fecha,
            'documentos' => $listDocumentos,
            'listMovimientosSucursal' => $listMovimientos,
            'listMovimientos' => $listMovimientos,
            'mensaje' => "",


        ]);
       
    }
    
    public function registrarConsignacion(Request $request)
    {

        //dd($request->all());
        if($request->reversar_pago==1){
            $consignacion = Consignaciones::find($request->codconsignacion);
            $consignacion->update([
                'estado' => 2,
                'estado_pago'=>2,
                'fecha_pago' => Carbon::now(),
                'observaciones' => $request->observaciones,
                'codusuariopaga' => \Auth::user()->id_usuario,
            ]);
            DB::table('rel_sucursal_movimiento')
            ->where('codconsignacion', $request->codconsignacion)
            ->update(['estado' => 2, 'estado_pago' => 2, 'valor_comision' => 0]);
            $sucursal = Sucursal::find($consignacion->codsucursal);
            $sucursal->movimientos_pagos()->attach($consignacion,[
                'tipo_movimiento' => 3,
                'valor_real' => $consignacion->valor,
                'valor_comision' => 0,
                'fecha_movimiento' => Carbon::now(),
                'estado' => 2,
                'saldo_inicial' => $sucursal->saldo,
                'saldo_final' => $sucursal->saldo-$consignacion->valor,
                'estado_pago' => 2
            ]);
            $saldo = $sucursal->saldo-$consignacion->valor;
            $sucursal->update([
                'saldo' => $saldo
            ]);
            $mensaje = "La consignación se registro con éxito";
        }else{
            $file = \Illuminate\Support\Facades\Input::file('soporte');
            $nombre = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'soportes';
            $mensaje = "Ocurrió un error a la hora de registrar la consignación";
            $error=1;
            if($file->move($destinationPath,$nombre)){
                $consignacion = Consignaciones::find($request->codconsignacion);
                $consignacion->update([
                    'estado_pago'=>1,
                    'fecha_pago' => Carbon::now(),
                    'observaciones' => $request->observaciones,
                    'codusuariopaga' => \Auth::user()->id_usuario,
                    'soporte' => $nombre
                ]);
                $mensaje = "La consignación se registro con éxito";
                $error=0;
            }
        }
        
        return redirect()->route('pagos.consignaciones-giros', [
            'mensaje' => "Operación realizada con éxito",
        ]);
    }

    
    
    public function registrarPagos(Request $request)
    {

        $pago = Pagos::find($request->codpago);
        if($pago->codconvenio==0){
            if($request->reversar_pago==1){
                $pago->update([
                    'estado' => 2,
                    'estado_pago'=>2,
                    'fecha_pago' => Carbon::now(),
                    'observaciones' => $request->observaciones,
                    'codusuariopaga' => \Auth::user()->id_usuario,
                ]);
                DB::table('rel_sucursal_movimiento')
                ->where('codpago', $request->codpago)
                ->update(['estado' => 2, 'estado_pago' => 2, 'valor_comision' => 0]);
                $sucursal = Sucursal::find($pago->codsucursal);
                $sucursal->movimientos_pagos()->attach($pago,[
                    'tipo_movimiento' => 6,
                    'valor_real' => $pago->valor,
                    'valor_comision' => 0,
                    'fecha_movimiento' => Carbon::now(),
                    'estado' => 2,
                    'saldo_inicial' => $sucursal->saldo,
                    'saldo_final' => $sucursal->saldo-$pago->valor,
                    'estado_pago' => 2
                ]);
                $saldo = $sucursal->saldo-$pago->valor;
                $sucursal->update([
                    'saldo' => $saldo
                ]);
                $mensaje = "La consignación se registro con éxito";
            }else{
                $file = \Illuminate\Support\Facades\Input::file('soporte');
                $nombre = time().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'soportes';
                $mensaje = "Ocurrió un error a la hora de registrar la consignación";
                $error=1;
                if($file->move($destinationPath,$nombre)){
                    $pago->update([
                        'estado_pago'=>1,
                        'fecha_pago' => Carbon::now(),
                        'observaciones' => $request->observaciones,
                        'codusuariopaga' => \Auth::user()->id_usuario,
                        'soporte' => $nombre
                    ]);
                    $mensaje = "La consignación se registro con éxito";
                    $error=0;
                }
                $campos = json_decode($pago->campos_js);
                //dd($campos);
                $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
                ->withData( array( 'user' => '00486812691','key'=>'9ded63f2ad133192c7b6f08777ce6be158391f33', 'num'=>'57'.str_replace(' ','',$campos->telefono),'sms'=>'El giro  '.str_pad($pago->codpago+2012, 4, "0", STR_PAD_LEFT).' ha sido aprobado. valor a recibir BsS '.$pago->observaciones.' Recuerde conservar la factura.'))
                ->post();
                $pago->update([
                    'reponse' => $response
                ]);
            }
        }else{
            if($request->reversar_pago==1){
                $pago->update([
                    'estado' => 2,
                    'estado_pago'=>2,
                    'fecha_pago' => Carbon::now(),
                    'observaciones' => $request->observaciones,
                    'codusuariopaga' => \Auth::user()->id_usuario,
                ]);
                DB::table('rel_sucursal_movimiento')
                ->where('codpago', $request->codpago)
                ->update(['estado' => 2, 'estado_pago' => 2, 'valor_comision' => 0]);
                $sucursal = Sucursal::find($pago->codsucursal);
                $sucursal->movimientos_pagos()->attach($pago,[
                    'tipo_movimiento' => 5,
                    'valor_real' => $pago->valor,
                    'valor_comision' => 0,
                    'fecha_movimiento' => Carbon::now(),
                    'estado' => 2,
                    'saldo_inicial' => $sucursal->saldo,
                    'saldo_final' => $sucursal->saldo-$pago->valor,
                    'estado_pago' => 2
                ]);
                $saldo = $sucursal->saldo-$pago->valor;
                $sucursal->update([
                    'saldo' => $saldo
                ]);
                $mensaje = "La consignación se registro con éxito";
            }else{
                $file = \Illuminate\Support\Facades\Input::file('soporte');
                $nombre = time().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'soportes';
                $mensaje = "Ocurrió un error a la hora de registrar la consignación";
                $error=1;
                if($file->move($destinationPath,$nombre)){
                    
                    $pago->update([
                        'estado_pago'=>1,
                        'fecha_pago' => Carbon::now(),
                        'observaciones' => $request->observaciones,
                        'codusuariopaga' => \Auth::user()->id_usuario,
                        'soporte' => $nombre
                    ]);
                    $mensaje = "La consignación se registro con éxito";
                    $error=0;
                }
            }
        }
        
        if($pago->codconvenio==0){
            return redirect()->route('convenios.otros', [
                'mensaje' => "Operación realizada con éxito",
            ]);
        }else{
            return redirect()->route('convenios.pagos', [
                'mensaje' => "Operación realizada con éxito",
            ]);
        }
        
    }

    
    
    public function consignacionConsultar(Request $request)
    {
        
        $consignacion = Consignaciones::find($request->codconsignacion);
        $convenio = Convenio::find($consignacion->codconvenio);

        return response()->json([
            'formulario' => view('convenios.partials.form-consignacion')->with([
                'convenio' => $convenio,
                'consignacion' => $consignacion,
            ])->render()
        ]);
    }
    
    public function pagoConsultar(Request $request)
    {
        
        $pago = Pagos::find($request->codpago);
        
        $convenio = Convenio::find($pago->codconvenio);

        return response()->json([
            'formulario' => view('convenios.partials.form-pagos')->with([
                'convenio' => $convenio,
                'pagos' => $pago,
            ])->render()
        ]);
    }

    public function indexDetallado(Request $request)
    {
        if(!blank(\Auth::user()->codempleado)){
            return redirect()->route('errors.empleado-403');
        }
        $listVentas = DB::table('movimientos AS m')->leftJoin('rel_sucursal_movimiento AS r','r.codmovimiento','=','m.codmovimiento')->leftJoin('tarifas AS t','t.codtarifa','=','m.codtarifa')->leftJoin('sucursales AS s','s.codsucursal','=','m.codsucursal');
        if(!blank($request->rango_fecha)){
            $rangoFechas = explode(' - ',$request->rango_fecha);
            $listVentas = $listVentas->whereBetween('m.fecha_transaccion', [
                Carbon::parse(str_replace('/','-',$rangoFechas[0]))->format('Y-m-d').' 00:00:00',
                Carbon::parse(str_replace('/','-',$rangoFechas[1]))->format('Y-m-d').' 23:59:59'
            ]);
        }else{
            $listVentas = $listVentas->whereBetween('m.fecha_transaccion', [
                Carbon::parse(Carbon::today())->format('Y-m-d').' 00:00:00',
                Carbon::parse(Carbon::today())->format('Y-m-d').' 23:59:59'
            ]);
        }
        if(!blank($request->codsucursal)){
            $listVentas = $listVentas->where('m.codsucursal','=',$request->codsucursal);
        }
        if(!blank($request->estado)&&$request->estado>0){
            $listVentas = $listVentas->where('m.estado','=',$request->estado);
        }
        $listVentas = $listVentas->orderBy('m.codmovimiento')->select('r.*','t.*','s.nombres AS nombreSucursal', 's.ubicacion', 'm.*')->get();
        if(blank($request->codsucursal)){
            $listVentas = [];
        }
        return view('asignaturas.index-detallado',[
            'listVentas' => $listVentas,
            'mensaje' => "",
            'menus' => Menu::listMenusHijos()
        ]);
    }


    public function indexTotal(Request $request)
    {
        if(!blank(\Auth::user()->codempleado)){
            return redirect()->route('errors.empleado-403');
        }
        $listVentas = DB::table('movimientos AS m')->join('rel_sucursal_movimiento AS r','r.codmovimiento','=','m.codmovimiento')->join('tarifas AS t','t.codtarifa','=','m.codtarifa')->join('sucursales AS s','s.codsucursal','=','r.codsucursal');
        if(!blank($request->rango_fecha)){
            $rangoFechas = explode(' - ',$request->rango_fecha);
            $listVentas = $listVentas->whereBetween('m.fecha_transaccion', [
                Carbon::parse(str_replace('/','-',$rangoFechas[0]))->format('Y-m-d').' 00:00:00',
                Carbon::parse(str_replace('/','-',$rangoFechas[1]))->format('Y-m-d').' 23:59:59'
            ]);
        }else{
            $listVentas = $listVentas->whereBetween('m.fecha_transaccion', [
                Carbon::parse(Carbon::today())->format('Y-m-d').' 00:00:00',
                Carbon::parse(Carbon::today())->format('Y-m-d').' 23:59:59'
            ]);
        }
        //dd($request->codsucursal);
        if(!blank($request->codsucursal)){
            $listVentas = $listVentas->where('r.codsucursal','=',$request->codsucursal);
        }
        if(!blank($request->estado)&&$request->estado>0){
            $listVentas = $listVentas->where('m.estado','=',$request->estado);
        }
        $listVentas = $listVentas->orderBy('m.codmovimiento')->select('m.*','t.*','s.nombres AS nombreSucursal','s.saldo AS saldoSucursal', 's.ubicacion', 'r.*')->get();
        $listDefinitiva = [];
        
        foreach($listVentas as $venta){
            if(!Arr::exists($listDefinitiva,$venta->codsucursal)){
                $listDefinitiva[$venta->codsucursal]["cant"] = 0;
                $listDefinitiva[$venta->codsucursal]["dinero"] = 0;
                $listDefinitiva[$venta->codsucursal]["saldo"] = 0;
                $listDefinitiva[$venta->codsucursal]["envios"] = 0;
                $listDefinitiva[$venta->codsucursal]["retiros"] = 0;
                $listDefinitiva[$venta->codsucursal]["iva"] = 0;
                $listDefinitiva[$venta->codsucursal]["ganancia"] = 0;
                $listDefinitiva[$venta->codsucursal]["ganancia_r"] = 0;
                $listDefinitiva[$venta->codsucursal]["ganancia_e"] = 0;
                $listDefinitiva[$venta->codsucursal]["saldo_envios"] = 0;
                $listDefinitiva[$venta->codsucursal]["saldo_retiros"] = 0;
                $listDefinitiva[$venta->codsucursal]["costo"] = 0;
            }
            $valor_comision = $venta->costo_envia;
            $valor_comision = blank($venta->porcentaje_envia) ? $valor_comision : $valor_comision+$venta->valor_real*$venta->porcentaje_envia/100;
            $valor_comisionr = $venta->costo_paga;
            $valor_comisionr = blank($venta->porcentaje_envia) ? $valor_comisionr : $valor_comisionr+$venta->valor_real*$venta->porcentaje_envia/100;
                                            
            $valor_iva = $venta->iva_costo*1000/100;
            $venta->iva_porcentaje = "1.".$venta->iva_porcentaje;
            $costo = $venta->costo==0 ? ($venta->porcentaje*$venta->valor/100) : ($venta->costo) + ($venta->porcentaje*$venta->valor/100); 
            //$valor_iva = blank($venta->iva_porcentaje) ? $valor_iva : $costo - $costo/$venta->iva_porcentaje;  
            $valor_iva = blank($venta->iva_porcentaje) ? $valor_iva : ($costo-$valor_comision-$valor_comisionr)-($costo-$valor_comision-$valor_comisionr)/$venta->iva_porcentaje;
                                                                         
            $ganancia = ($costo)-$valor_iva-$valor_comision-$valor_comisionr;
                                        
                $listDefinitiva[$venta->codsucursal]["nombre"]=$venta->nombreSucursal;
                $listDefinitiva[$venta->codsucursal]["costo"]+=$costo;
                $listDefinitiva[$venta->codsucursal]["dinero"]=$venta->saldoSucursal;
                
            if($venta->tipo_movimiento==1){
                $listDefinitiva[$venta->codsucursal]["cant"]++;
                $listDefinitiva[$venta->codsucursal]["iva"]+=$valor_iva;
                $listDefinitiva[$venta->codsucursal]["ganancia"]+=$ganancia;
                $listDefinitiva[$venta->codsucursal]["envios"]++;
                $listDefinitiva[$venta->codsucursal]["saldo"]+=$venta->valor_real;
                $listDefinitiva[$venta->codsucursal]["saldo_envios"]+= $venta->valor_real;
                $listDefinitiva[$venta->codsucursal]["ganancia_e"]+= $valor_comision;
            }else{
                $listDefinitiva[$venta->codsucursal]["cant"]++;
                $listDefinitiva[$venta->codsucursal]["ganancia"]+=$ganancia;
                $listDefinitiva[$venta->codsucursal]["retiros"]++;
                $listDefinitiva[$venta->codsucursal]["saldo"]-=$venta->valor_real;
                $listDefinitiva[$venta->codsucursal]["saldo_retiros"]+= $venta->valor_real;
                $listDefinitiva[$venta->codsucursal]["ganancia_r"]+= $valor_comisionr;
            }
        }
        if(blank($request->codsucursal)){
            $listDefinitiva = [];
        }
        return view('asignaturas.index-total',[
            'listVentas' => $listVentas,
            'listDefinitivas' => $listDefinitiva,
            'mensaje' => "",
            'menus' => Menu::listMenusHijos()
        ]);
    }






    public function reportesEstadisticos(Request $request)



    {
        if(!blank(\Auth::user()->codempleado)){
            return redirect()->route('errors.empleado-403');
        }

        return view('asignaturas.reportes-estadisticos',[

            'mensaje' => "",

            'listProductos' => DB::table('productos as p')->join('categorias as c','c.id_categoria','=','p.codcategoria')->pluck(DB::raw("(c.nombre||' - '||p.nombre) as nombre"), 'p.codproducto'),

            'listVentas' => (\Auth::user()->email==1) ? DB::table('venta as v')->join('inventario as i','i.codinventario','=','v.codinventario')->join('productos as p','i.codproducto','=','p.codproducto')->join('usuarios as u','v.cod_punto','=','u.id_usuario')->where('u.id_usuario','=',\Auth::user()->id_usuario)->orderBy('codventa','DESC')->get() : DB::table('venta as v')->join('inventario as i','i.codinventario','=','v.codinventario')->join('productos as p','i.codproducto','=','p.codproducto')->join('usuarios as u','v.cod_punto','=','u.id_usuario')->orderBy('codventa','DESC')->get(),

            'listVendedores'=>DB::table('usuarios')->where('email',1)->pluck(DB::raw("numero_identificacion||' - '||nombres||' '||apellidos as total"), 'id_usuario'),

            'listGrados' => DB::table('curso')->orderBy('orden')->pluck('nombrecurso', 'codcurso'),

            'menus' => Menu::listMenusHijos()

            ]);



    }





    public function reportesEstados(Sucursal $sucursal, Request $request)



    {


        return view('asignaturas.reportes-estados',[

            'sucursal' => $sucursal,

            'mensaje' => "",

            'listProductos' => DB::table('productos as p')->join('categorias as c','c.id_categoria','=','p.codcategoria')->pluck(DB::raw("(c.nombre||' - '||p.nombre) as nombre"), 'p.codproducto'),

            'listVentas' => (\Auth::user()->email==1) ? DB::table('venta as v')->join('inventario as i','i.codinventario','=','v.codinventario')->join('productos as p','i.codproducto','=','p.codproducto')->join('usuarios as u','v.cod_punto','=','u.id_usuario')->where('u.id_usuario','=',\Auth::user()->id_usuario)->orderBy('codventa','DESC')->get() : DB::table('venta as v')->join('inventario as i','i.codinventario','=','v.codinventario')->join('productos as p','i.codproducto','=','p.codproducto')->join('usuarios as u','v.cod_punto','=','u.id_usuario')->orderBy('codventa','DESC')->get(),

            'listVendedores'=>DB::table('usuarios')->where('email',1)->pluck(DB::raw("numero_identificacion||' - '||nombres||' '||apellidos as total"), 'id_usuario'),

            'listGrados' => DB::table('curso')->orderBy('orden')->pluck('nombrecurso', 'codcurso'),

            'menus' => Menu::listMenusHijos()

        ]);

    }



    public function consignacionesProcesar(Request $request)



    {

        $movimiento = Consignacion::find($request->codmovimiento);

        $movimiento->update([

            'estado'=> $request->estado,

            'fecha_modificacion' => Carbon::now()

        ]);

        $sucursal = Sucursal::find($movimiento->codsucursal);

        if($request->estado==1){

            $sucursal->update([

                'saldo'=>$sucursal->saldo-$movimiento->valor

            ]);

        }

        return response()->json([

            'mensaje' => 'Operación exitosa'

        ]);



    }







    public function consignaciones(Request $request)
    {
        
        $sucursal = Sucursal::first();
        if(!blank(\Auth::user()->codempleado)&&\Auth::user()->perfil!=2&&$sucursal->codsucursal!=session('sucursal')->codsucursal){
            return redirect()->route('errors.empleado-403');
        }
        $documentos = $sucursal->documentos;
        $listDocumentos = [];
        foreach($documentos as $documento){
            $listDocumentos[$documento->tipo] = $documento;
        }
        $listMovimientos = DB::table('rel_sucursal_movimiento AS r')->leftjoin('tarifas AS t', function ($join) {
            $join->on('t.codtarifa', '=', 'r.codtarifa')
                ->whereNotNull('r.codmovimiento');
            })->join('sucursales AS s','s.codsucursal','=','r.codsucursal')->where('r.codsucursal','=',$sucursal->codsucursal)->select('r.*','t.*','s.nombres AS nombreSucursal', 's.ubicacion', 'r.codtarifa', 'r.estado')->get();
        
            return view('convenios.consignaciones',[
            'sucursal' => $sucursal,
            'fecha' => $request->rango_fecha,
            'documentos' => $listDocumentos,
            'listMovimientosSucursal' => $listMovimientos,
            'listMovimientos' => $listMovimientos,
            'mensaje' => "",


        ]);
       
    }







    public function indexEvidencias(Request $request)



    {



        return response()->json([



            'html' => view('asignaturas.evidencias',[



                'asignatura' => Asignatura::find($request->codasignatura),



            ])->render()



        ]);



    }







    public function editarEvidencia(Request $request)



    {



        $dataEvidencia=[



            'descripcion' => $request->descripcion,// character varying(100),



            'estado' =>  ($request->estado) ? 1 : 2 ,// character varying(100),



       ];



        $evidencia = Evidencia::find($request->codevidencia);



        $evidencia->update($dataEvidencia);



        



        return response()->json([



            'mensaje' => 'Evidencia actualizada con éxito'



        ]);



    }



    



    public function editarAsignatura(Request $request)



    {



        $dataAsignatura=[



            'nombreasignatura' => $request->nombreasignatura,// character varying(100),



            'observacion' => $request->lema,// character varying(100),



            'fecha_creacion' => Carbon::today(),// timestamp without time zone DEFAULT now(),



            'intensidad' => $request->ih,



        ];



        $asignatura = Asignatura::find($request->codasignatura);



        $asignatura->update($dataAsignatura);



        if(!blank($request->coddocente)){



            $dataAttach = [$request->coddocente => $request->coddocente];



            if(!blank($request->coddocenteaux)){



                $dataAttach[$request->coddocenteaux] = $request->coddocenteaux;



            }



            //$docente = Docente::find($request->coddocente);



            $asignatura->docente()->sync($dataAttach);



        }



        return response()->json([



            'mensaje' => 'Asignatura evidencia con éxito'



        ]);



    }







    public function cargarExcelEvidencias(Request $request)



    {



        try {



            $contadorFila = 1;



            $asignatura = Asignatura::find($request->codasignatura);



            Excel::load($request->archivo, function ($reader) use ($asignatura) {



                $excel = $reader->get();



                // iteracción



                $reader->each(function ($row) use ($asignatura) {



                    $evidencia = new Evidencia([



                        'descripcion' => $row->evidencia,



                        'estado' => 1



                    ]);



                    $evidencia->save();



                    $asignatura->evidencias()->attach($evidencia, [



                        'año' => Carbon::today()->year,



                        'estado' => 1



                    ]);



                });



            });



            $notificacion = [



                'mensaje' => trans("colegios.cargue_excel_exito")



            ];



        } catch (\Exception $e) {



            $notificacion = [



                'mensaje' => trans("colegios.error")



            ];



            report($e);



        }



        return redirect()->route('asignaturas.index', [



            'codcurso' => $asignatura->curso,



            'mensaje' => "Evidencias guardadas con éxito",



        ]);



        //return response()->json($notificacion);



    }



    



    /**



     *



     * @tutorial Method Description: carga la vista de crear pagina



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function crearAsignatura(Request $request)



    {



        $dataAsignatura=[



            'nombreasignatura' => $request->nombreasignatura,// character varying(100),



            'estado' => 1,// numeric,



            'observacion' => $request->observacion,// character varying(100),



            'fecha_creacion' => Carbon::today(),// timestamp without time zone DEFAULT now(),



            'curso' => $request->codcurso,// integer,



            'intensidad' => $request->ih,



        ];



        $asignatura = new Asignatura($dataAsignatura);



        $asignatura->save();



        $docente = Docente::find($request->coddocente);



        $asignatura->docente()->attach($docente);



        $listAsignaturas = DB::table('asignatura AS a')->leftJoin('rel_asignaturadocente AS rel_a','rel_a.codasignatura','=','a.codasignatura')->where('curso','=',$request->codcurso)->orderBy('curso')->select('a.*','rel_a.coddocente')->get();



        $request->mensaje = "Asignatura guardada con éxito";



        



        return redirect()->route('asignaturas.index', [



            'codcurso' => $request->codcurso,



            'mensaje' => "Asignatura guardada con éxito",



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











    /**



     *



     * @tutorial Method Description: carga la vista de crear pagina



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function vistaMatricula(Request $request)



    {



        $estudiante = Estudiante::find($request->codestudiante);



        $grado = $estudiante->cursoActivo();



        return view('estudiantes.partials.matricular',[



            "estudiante" => $estudiante,



            "grado" => $grado,



            "gradoSiguiente" => Curso::find($grado->siguiente),



        ]);



    }







    /**



     *



     * @tutorial Method Description: carga la vista de crear pagina



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    







    /**



     *



     * @tutorial Method Description: carga la vista de crear pagina



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function procesarMatricula(Request $request)



    {



        $estudiante = Estudiante::find($request->codestudiante);



        $grado = $estudiante->cursoActivo();



        DB::table('rel_cursoestudiante')



        ->where('codrelcursoestudiante', $grado->codrelcursoestudiante)



        ->update(['estado' => ($request->matricular) ? 3 : 4 ]);



        if($request->promocion==ESiNo::index(ESiNo::Si)->getId()){



            $gradoSiguiente = Curso::find($grado->siguiente);



            $estudiante->cursos()->attach($gradoSiguiente, [



                'numero' => $grado->numero,



                'observacion' => "ESTUDIANTE PROMOVIDO Y MATRICULADO",



                'estado' => 1,



                'fecha' => Carbon::now()



            ]);



        }else{



            $estudiante->cursos()->attach($grado, [



                'numero' => $grado->numero,



                'observacion' => "ESTUDIANTE NO PROMOVIDO Y MATRICULADO",



                'estado' => 1,



                'fecha' => Carbon::now()



            ]);



        }



        



        return response()->json([



            'mensaje' => 'Matricula registrada con éxito'



        ]);



    }







    /**



     *



     * @tutorial Method Description: Show the application dashboard.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function footerPrincipal()



    {



        return view('principal.editar-footer',["pagina" => Pagina::find(0),"menus" => []]);



    }











    /**



     *



     * @tutorial Method Description: Carga la vista de editar pagina



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function editarPagina(Pagina $pagina)



    {



        return view('principal.editar-pagina',[



            'pagina'=>$pagina,



            'menus' => Menu::listMenusHijos()



            ]);



    }







    



    /**



     *



     * @tutorial Method Description: Guarda la pagina



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function store(Request $request)



    {



        //Valida que el texto editar este vacio para saber si se esta editando la informacion de la pagina (nombre, activo, etc) o la informacion del contenido (background, contenido)



        if(blank($request->editar)){



            //Se obtienen los atributos de la pagina



            $datosPagina = [



                'pagina_editor' => $request->pagina_editor,



                'pagina_mostrar' => $request->pagina_mostrar,



                'img_background' => $request->img_background,



                'estado' => EEstadoPagina::index(EEstadoPagina::GUARDADA)->getId()



            ];



            //Se valida si la pagina existe o no, para crear o actualizar



            if(blank($request->id_pagina)){



                //Se unen los atributos obtenidos anteriormente, con los atributos faltantes a la hora de crear 



                $datosPagina = array_merge($datosPagina,[



                    'fecha_creacion' => Carbon::now(),



                    'id_usuario_registra' => Auth::user()->id_usuario,



                    'id_menu' => $request->id_menu,



                    'principal' => $request->get('principal',ESiNo::index(ESiNo::No)->getId()),



                    'activo' => $request->get('activo',ESiNo::index(ESiNo::No)->getId()),



                    'titulo' => $request->nombre



                ]);



                //Si la pagina es principal actualiza todos los hijos de ese menu a 2 para que solo quede como principal esta pagina



                if($datosPagina["principal"] == ESiNo::index(ESiNo::Si)->getId()){



                    Menu::find($datosPagina["id_menu"])->paginas()->update([



                        "principal" => ESiNo::index(ESiNo::No)->getId()



                    ]);



                }



                //Se guarda la pagina



                $pagina = Pagina::create($datosPagina);







            }else{



                //Se consulta la pagina a actualizar



                $pagina = Pagina::find($request->id_pagina);



                



                $datosPagina = array_merge($datosPagina,[



                    'fecha_modificacion' => Carbon::now(),



                    'id_usuario_modifica' => Auth::user()->id_usuario



                ]);



                //Se actualiza la informacion de la pagina



                $pagina->update($datosPagina);



            }



            return response()->json([



                'success' => 'success',



                'idPagina' => $pagina->id_pagina



            ]);



        }else{



            //Captura el estado anterior de la pagina para saber si pasa a Publicada o Suspendida



            $estadoAnterior = $request->estadoAnterior;



            



            //Si la pagina es principal actualiza todos los hijos de ese menu a 2 para que solo quede como principal esta pagina



            $principal = $request->get('principal',ESiNo::index(ESiNo::No)->getId());



            if($principal == ESiNo::index(ESiNo::Si)->getId()){



                Menu::find($request->id_menu)->paginas()->update([



                    "principal" => ESiNo::index(ESiNo::No)->getId()



                ]);



            }



            $estadoActual = ($estadoAnterior == EEstadoPagina::index(EEstadoPagina::GUARDADA)->getId()) ? ((blank($request->estado)) ? $estadoAnterior : EEstadoPagina::index(EEstadoPagina::PUBLICADA)->getId()) : ((blank($request->estado)) ? EEstadoPagina::index(EEstadoPagina::SUSPENDIDA)->getId() : EEstadoPagina::index(EEstadoPagina::PUBLICADA)->getId());



            //Se consulta la pagina a actualizar



            $pagina = Pagina::find($request->id_pagina);



            $datosPagina = [



                        'titulo' => $request->titulo,



                        'id_menu' => $request->id_menu,



                        'estado' => $estadoActual,



                        'principal' => $principal,



                        'fecha_modificacion' => Carbon::now(),



                        'id_usuario_modifica' => Auth::user()->id_usuario



                    ];



            //Se actualiza la informacion de la pagina



            $pagina->update($datosPagina);



            return response()->json([



                'tablaPaginas' => view('principal.partials.tabla-paginas')->with([



                    'listPaginas' => Pagina::listadoPagina(),



                ])->render()



            ]);



        }



    }







    







    /**



     *



     * @tutorial Method Description: Consulta los ficheros para mostrarlos en el administrador de archivos



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function obtenerFicheros(Request $request){



        



        //Obtiene la ruta enviada del lado del cliente



        $rutaActual = $request->get('rutaActual');



        $rutaMiniaturas = 'storage/files/watermark';



        //Si la ruta no existe quiere decir que se encuentra en la ventana principal



        $rutaActual = (blank($rutaActual)) ? 'public/files/' : $rutaActual ;



        //Consulta los archivos en el directorio de storage



        $listArchivos = Storage::files($rutaActual);



        $listArchivos = array_reverse($listArchivos);



        if($request->ext=='img'){



            $listArchivos = preg_grep('/^.*\.(jpg|jpeg|png|gif)$/i', $listArchivos);



        }else if($request->ext=='pdf'){



            $listArchivos = preg_grep('/^.*\.(pdf)$/i', $listArchivos);



        }



        //Consulta los folders del directorio



        $listCarpetas = Storage::directories($rutaActual);



        //Explodea la ruta para validar si se pinta la carpeta de salir nivel o no



        $ruta = explode('/',str_replace('../','*',$rutaActual));



        $contadorHaciaAtras = 0;



        $contadoVacios = 0;



        foreach ($ruta as $value) {



            $contadorHaciaAtras+= ($value=="*") ? 1 : 0;



            $contadoVacios+= (blank($value)) ? 1 : 0;



        }



        $salirNivel = (count($ruta)-($contadorHaciaAtras+$contadorHaciaAtras+$contadoVacios) == 2) ? false : true;



        //Llama el partials del administrador de archivos, le pasa los parametros luego retorna los datos el html de dicho partial



        return response()->json([



            'html' => view('principal.partials.file_manager')->with([



                'listCarpetas' => $listCarpetas,



                'listArchivos' => $listArchivos,



                'rutaActual' => $rutaActual,



                'salirNivel' => $salirNivel,



                'miniaturas' => $rutaMiniaturas,



                'wizard' => $request->wizard



                ])->render()



        ]);



        //return [$files, $listCarpetas];



    }







    /**



     *



     * @tutorial Method Description: Realiza el cropeado y guardado de la imagen



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function guardarImagenCrop(Request $request)



    {



        //Se obtiene el archivo subido



        $file = \Illuminate\Support\Facades\Input::file('file');



        //Se capturan las propiedades del cropper



        $opcionesCropper = json_decode($request->dataCrop);



        //Se declaran los meses para las carpetas



        $meses = [



            '1' => 'ENERO',



            '2' => 'FEBRERO',



            '3' => 'MARZO',



            '4' => 'ABRIL',



            '5' => 'MAYO',



            '6' => 'JUNIO',



            '7' => 'JULIO',



            '8' => 'AGOSTO',



            '9' => 'SEPTIEMBRE',



            '10' => 'OCTUBRE',



            '11' => 'NOVIEMBRE',



            '12' => 'DICIEMBRE'



        ];



        //Se crea la ruta



        $destinationPath = config('app.files').$meses[date('n')+1];



        //Se crea el directorio enc aos que no exista



        Storage::makeDirectory($destinationPath);



        //Se asigna un nombre a la imagen



        $png_url = time().".jpg";



        //Se asigna la ruta completa que de la imagen para guardarla



        $rutaImg = public_path('../storage/app/'.$destinationPath.'/'.$png_url);



        $rutaImgWaterMark = public_path('../storage/app/public/files/watermark/'.$png_url);



        $parametro = Parametro::find(1);



        try{



            //Se crea una nueva instancía de una imagen a partir del archivo subido



            $img =\Image::make(file_get_contents($file->getRealPath()))->encode('jpg', 75);



            



            //Se aumenta el maximo de memoria, valor definido por eminson



            ini_set('memory_limit', '2500M');



            //Se valida que exista el rotate en la imagen y que este sea mayor a 0



            if(property_exists($opcionesCropper, 'rotate') && (int)$opcionesCropper->rotate!=0){



                //Se calcula el angulo a rotar ya que el cropper.js trabaja con valores diferentes



                //Los valores de Image intervention son contrarios a las manecillas del reloj => http://image.intervention.io 



                $angulo = ($opcionesCropper->rotate>0) ? (360 + $opcionesCropper->rotate*-1) : $opcionesCropper->rotate*-1;



                //Se rota la imagen y se coloca fondo blanco por si llega a quedar pedazos de la imagen fuera del cropper



                $img->rotate($angulo,'#FFFFFF');



            }



            //Se corta la imagen con los parametros seleccionados por el usuario



            //Los valores de Image intervention son enteros, cropper.js es mucho mas precisa y admite decimales



            //Teniendo en cuenta esto, se redondean hacia abajo los valores => http://image.intervention.io 



            $img->crop(round($opcionesCropper->width,0), round($opcionesCropper->height,0), round($opcionesCropper->x,0), round($opcionesCropper->y,0));



            //Se valida que la dimension de la imagen no sea mayor a la permitida



            if($img->width()>$parametro->ancho_galeria){



                $ratio=$parametro->ancho_galeria/$img->width();



                $altura=$img->height()*$ratio;



                $img->resize($parametro->ancho_galeria,$altura);



            }



            //Se guarda la imagen en la ruta especificada y se comprime para disminuir el peso



            $img->save($rutaImg, 40);



            //se crea y guarda una imagen miniatura de la imagen original



            $img->resize(170,127);



            $img->save($rutaImgWaterMark);



            $rutaWatermark = url('storage/files/watermark/'.$png_url);



            $ruta = url('storage/files/'.$meses[date('n')+1].'/'.$png_url);



        } catch (\Exception $e) {



            report($e);



        }



        //Se retorna a la vista



        return response()->json([



            'success' => 'success',



            'rutaWatermark' => $rutaWatermark,



            'ruta' => $ruta,



            'nombre' => date('m-d-Y G:ia',str_replace('.jpg','',$png_url))



        ]);



        



    }











    /**



     *



     * @tutorial Method Description: Realiza el cropeado y guardado de la imagen



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function cortarImagenCrop(Request $request)



    {



        //Se obtiene el archivo subido



        $opcionesCropper = json_decode($request->dataCrop);



        //Se asigna la ruta completa que de la imagen para guardarla



        $ruta = config('app.files').$request->imagen;



        $rutaImg = public_path('../storage/app/public/files/'.str_replace('storage/files/','',$request->imagen));



        //Se consulta el nombre para cambiar la marca de agua



        $nombreArchivo = explode('/',$request->imagen);



        //Sec rea la ruta de la marca de agua



        $rutaImgWaterMark = public_path('../storage/app/public/files/watermark/'.$nombreArchivo[count($nombreArchivo)-1]);



        try{



            ini_set('memory_limit', '250541502



            0M');



            //Se crea la imagen con la ruta establecida



            $img = \Image::make($rutaImg);



            //Se valida que exista el rotate en la imagen y que este sea mayor a 0



            if(property_exists($opcionesCropper, 'rotate') && (int)$opcionesCropper->rotate!=0){



                //Se calcula el angulo a rotar ya que el cropper.js trabaja con valores diferentes



                //Los valores de Image intervention son contrarios a las manecillas del reloj => http://image.intervention.io 



                $angulo = ($opcionesCropper->rotate>0) ? (360 + $opcionesCropper->rotate*-1) : $opcionesCropper->rotate*-1;



                //Se rota la imagen y se coloca fondo blanco por si llega a quedar pedazos de la imagen fuera del cropper



                $img->rotate($angulo,'#FFFFFF');



            }



            //Se corta la imagen con los parametros seleccionados por el usuario



            //Los valores de Image intervention son enteros, cropper.js es mucho mas precisa y admite decimales



            //Teniendo en cuenta esto, se redondean hacia abajo los valores => http://image.intervention.io 



            $img->crop(round($opcionesCropper->width,0), round($opcionesCropper->height,0), round($opcionesCropper->x,0), round($opcionesCropper->y,0));



            //Se guarda la imagen



            $img->save($rutaImg,70);



            //se crea y guarda una imagen miniatura de la imagen original



            $img->resize(170,127);



            $img->save($rutaImgWaterMark);







        } catch (\Exception $e) {



            report($e);



        }



        //Se retorna a la vista



        return response()->json([



            'success' => 'success'



        ]);



        



    }



    /**



     *



     * @tutorial Method Description: Elimina el archivo y la miniatura que el usuario seleccione



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function eliminarArchivo(Request $request){



        //Divide el nombre para poder capturar solo la carpeta final y el nombre.jpg



        $nombreArchivo = explode('/',$request->rutaArchivo);



        //Divide el nombre de la miniatura para poder capturar solo la carpeta final y el nombre.jpg



        $nombreMiniatura = explode('/',$request->rutaMiniatura);



        //Elimina el archivo 



        $rutaEliminar = config('app.files').$nombreArchivo[count($nombreArchivo)-2]."/".$nombreArchivo[count($nombreArchivo)-1];



        //Elimina el archivo miniatura



        $rutaEliminarMiniatura = config('app.files').$nombreMiniatura[count($nombreArchivo)-2]."/".$nombreMiniatura[count($nombreArchivo)-1];



        $eliminar = Storage::delete($rutaEliminar);



        $eliminarMiniatura = Storage::delete($rutaEliminarMiniatura);



        //Se retorna a la vista



        return response()->json([



            'success' => ($eliminar && $eliminarMiniatura) ? true : false,



            'rutaActual' => $request->rutaActual



        ]);



    }











    /**



     *



     * @tutorial Method Description: Show the application dashboard.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function visualizarPagina(Pagina $pagina)



    {



        //$pagina = Pagina::find(1);



        $paginaFooter = Pagina::find(0);



        return view('principal.visualizar-pagina',[



            'pagina'=>$pagina,



            'footer' => $paginaFooter



            ]);



    }







    



    /**



     *



     * @tutorial Method Description: Guarda la imagen o los pdgs que el usuario envie



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function guardarArchivo(Request $request){



        $file = \Illuminate\Support\Facades\Input::file('file');



        //Se declaran los meses para las carpetas



        $meses = [



            '1' => 'ENERO',



            '2' => 'FEBRERO',



            '3' => 'MARZO',



            '4' => 'ABRIL',



            '5' => 'MAYO',



            '6' => 'JUNIO',



            '7' => 'JULIO',



            '8' => 'AGOSTO',



            '9' => 'SEPTIEMBRE',



            '10' => 'OCTUBRE',



            '11' => 'NOVIEMBRE',



            '12' => 'DICIEMBRE'



        ];



        //Se crea la ruta



        $destinationPath = config('app.files').$meses[date('n')+1];



        //Se crea el directorio enc aos que no exista



        Storage::makeDirectory($destinationPath);



        



        try{



            if($request->tipoArchivo=='img'){



                //Se asigna un nombre a la imagen



                $png_url = time().".jpg";



                //Se asigna la ruta completa que de la imagen para guardarla



                $rutaImg = public_path('../storage/app/'.$destinationPath.'/'.$png_url);



                $rutaImgWaterMark = public_path('../storage/app/public/files/watermark/'.$png_url);



                //Se crea una nueva instancía de una imagen a partir del archivo subido



                $img =\Image::make(file_get_contents($file->getRealPath()))->encode('jpg', 75);



                //Se valida que la dimension de la imagen no sea mayor a la permitida



                if($img->width()>$parametro->ancho_galeria){



                    $ratio=$parametro->ancho_galeria/$img->width();



                    $altura=$img->height()*$ratio;



                    $img->resize($parametro->ancho_galeria,$altura);



                }



                //Se guarda la imagen en la ruta especificada y se comprime para disminuir el peso



                $img->save($rutaImg, 40); 



                //Se crea y guarda una miniatura de la imagen



                $img->resize(170,127);



                $img->save($rutaImgWaterMark);



            }else{



                //Le asigna el nombre al pdf



                $fileName =  time().'.' . $file->getClientOriginalExtension();



                //Lo guarda en la carpeta correspondiente



                $uploaded = Storage::put($destinationPath."/" . $fileName, file_get_contents($file->getRealPath()));



            }



            



        } catch (\Exception $e) {



            report($e);



        }



        //Se retorna a la vista



        return response()->json([



            'success' => 'success'



        ]);



    }







    /**



     * Display the specified resource.



     *



     * @param int $id



     * @return \Illuminate\Http\Response



     */



    public function show($id)



    {



        //



    }







    /**



     * Show the form for editing the specified resource.



     *



     * @param int $id



     * @return \Illuminate\Http\Response



     */



    public function edit($id)



    {



        //



    }







    /**



     * Update the specified resource in storage.



     *



     * @param \Illuminate\Http\Request $request



     * @param int $id



     * @return \Illuminate\Http\Response



     */



    public function update(Request $request, $id)



    {



        //



    }







    /**



     *



     * @tutorial Method Description: Consulta la informacion de la pagina



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function consultarPagina(Request $request)



    {



        $pagina = Pagina::find($request->id_pagina);



        return response()->json([



            'informacionPagina' => view('principal.partials.editar-paginas')->with([



                'pagina' => $pagina,



                'menus' => Menu::listMenusHijos()



            ])->render(),



        ]);



    }







    /**



     *



     * @tutorial Method Description: Muestra la iamgen en la vista para recortarla



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function mostrarImagen(Request $request)



    {



        



        return view('principal.cortar-imagen',[



            'imagen'=>$request->ruta,



            'numero' => rand(1, 100)



        ]);



    



    }







    /**



     * Remove the specified resource from storage.



     *



     * @param int $id



     * @return \Illuminate\Http\Response



     */



    public function destroy($id)



    {



        //



    }







    /**



     *



     * @tutorial Method Description: Carga la vista de administrar paginas



     * @author Bayron Tarazona ~ bayronthz@gmail.com



     * @since {02/10/2018}



     */



    public function administrarPagina(Request $request){



        



        return view('principal.administrar-paginas',[



            'tablaPaginas' => view('principal.partials.tabla-paginas')->with([



                'listPaginas' => Pagina::listadoPagina($request->id_menu, $request->rango_fechas, $request->principal),



            ])->render(),



            'menus' => Menu::listMenusHijos()



        ]);



    }











    public function eliminarPagina(Request $request)



    {



        $pagina = Pagina::find($request->id_pagina);



        $pagina->delete();



        return response()->json([



            'tablaPaginas' => view('principal.partials.tabla-paginas')->with([



                'listPaginas' => Pagina::listadoPagina(),



            ])->render()



        ]);



    }







}



