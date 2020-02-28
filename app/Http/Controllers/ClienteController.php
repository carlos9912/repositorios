<?php

namespace App\Http\Controllers;



use Curl;

use Carbon\Carbon;

use App\Enums\ESiNo;
use App\Models\Menu;

use App\Models\Curso;
use App\Models\Soat;

use App\Models\Pagos;

use App\Enums\EActivo;

use App\Models\Pagina;

use App\Models\Perfil;

use App\Models\Winred;

use App\Models\Cliente;

use App\Models\Permiso;

use App\Models\Recarga;

use App\Models\Tarifas;

use App\Models\Usuario;

use App\Models\Convenio;

use App\Models\Empleado;

use App\Models\Sucursal;

use App\Models\Parametro;

use App\Models\Estudiante;

use App\Models\Movimiento;

use Illuminate\Support\Arr;

use App\Enums\EEstadoPagina;

use Illuminate\Http\Request;
use App\Models\Consignaciones;
use App\Models\RecargaPaquete;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoEmail;



class ClienteController extends Controller

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


    public function buscarGiro(Request $request)
    {
        $cliente = Cliente::find($request->codcliente);
        $giro = $cliente->movimientos_pendientes()->where('codigo','=',$request->codigo)->first();
        if(blank($giro)){
            $giroTemp = DB::table('movimientos AS m')->where('codigo','=',$request->codigo)->where('estado','=','2')->first();
            if(!blank($giro) && $giroTemp->identificacion_recibe==$cliente->identificacion){
                $giro = Movimiento::find($giroTemp->codmovimiento);
                $giro->update([
                    'codcliente_recibe'=>$cliente->codcliente
                ]);
            }
        }
        return response()->json([
            'html' => view('principal.formulario-retiro')->with([
                'cliente' => blank($cliente) ? new Cliente() : $cliente,
                'cliente_envia' => blank($giro) ? new Cliente() : Cliente::find($giro->codcliente_envia),
                'giro' => blank($giro) ? new Movimiento() : $giro
                
            ])->render(),
            'existe' => blank($giro) ? 0 : ($giro->estado==2 ? 1 : 2)
        ]);
        exit();
    }



    public function buscarCliente(Request $request)
    {
        $cliente = DB::table('clientes AS c')->where('identificacion','=',$request->identificacion)->first();
        if(blank($cliente)){
             $cliente = new Cliente();
        }else{
            $cliente=Cliente::find($cliente->codcliente);
        }
        return response()->json([
            'html' => view('principal.formulario-envio')->with([
                'cliente' => $cliente,
                'cliente_recibe' => new Cliente(),
                'tk' => ($request->tk)
            ])->render(),
            'ti' => $cliente->tipo_documento,
            'identificacion' => blank($cliente->identificacion) ? -1 : $cliente->identificacion,
           'codigo' => $cliente->codcliente
        ]);
        exit();
    }


    public function buscarHistorial(Request $request)
    {
        $cliente = DB::table('clientes AS c')->where('identificacion','=',$request->identificacion)->first();
        if(blank($cliente)){
             $cliente = new Cliente();
        }else{
            $cliente=Cliente::find($cliente->codcliente);
        }
        
        return response()->json([
            'html' => view('principal.formulario-envio')->with([
                'cliente' => $cliente,
                'cliente_recibe' => new Cliente(),
                'tk' => ($request->tk)
            ])->render(),
            'identificacion' => blank($cliente->identificacion) ? -1 : $cliente->identificacion,
        ]);
        exit();
    }

   public function buscarClienteRecibe(Request $request)
    {
        
        
        $cliente=Cliente::find($request->codcliente);
        $cliente_recibe = DB::table('clientes AS c')->where('identificacion','=',$request->identificacion_recibe)->first();
        if(blank($cliente_recibe)){
            $cliente_recibe = new Cliente();
       }else{
           $cliente_recibe=Cliente::find($cliente_recibe->codcliente);
       }
        return response()->json([
            'html' => view('principal.formulario-envio')->with([
                'cliente' => $cliente,
                'cliente_recibe' => $cliente_recibe,
                'tk' => ($request->tk)
            ])->render(),
            'identificacion' => blank($cliente->identificacion) ? -1 : $cliente->identificacion,
        ]);
        exit();
    }

    public function consultarTarifas(Request $request)
    {
        

        $cliente=Cliente::find($request->codcliente);
        $tarifas = DB::table('tarifas AS t')->where('estado','=',1)->where('valor_inicial','<=',$request->valor)->where('valor_final','>=',$request->valor)->first();
            
        return response()->json([
            'html' => $tarifas
        ]);
        exit();
    }

    public function enviarGiro(Request $request)
    {
       $cliente = DB::table('clientes AS c')->where('identificacion','=',$request->identificacion)->first();
       $cliente=Cliente::find($cliente->codcliente);
       $clienteRecibe = DB::table('clientes AS c')->where('identificacion','=',$request->identificacion_recibe)->first();
       $tarifas = DB::table('tarifas AS t')->where('estado','=',1)->where('valor_inicial','<=',$request->valor)->where('valor_final','>=',$request->valor)->first();
       
       $tarifa = Tarifas::find($tarifas->codtarifa);
       $empleado = Empleado::find(\Auth::user()->codempleado);
       //dd(\Auth::user());
       $sucursal = Sucursal::find(session('sucursal')->codsucursal);
       $celular = blank($request->celular) ? $clienteRecibe->celular : $request->celular;
       $valor_iva = $tarifas->iva_costo*1000/100;
       $tarifas->iva_porcentaje = "1.".$tarifas->iva_porcentaje;
       $costo = $tarifas->costo==0 ? ($tarifas->porcentaje*$request->valor/100) : ($tarifas->costo) + ($tarifas->porcentaje*$request->valor/100); 
        
       $movimiento = $cliente->movimientos()->save(new Movimiento([
           'codcliente_envia' => $cliente->codcliente,// integer,
           'codcliente_recibe' => (blank($clienteRecibe)) ? NULL : $clienteRecibe->codcliente,// integer,
           'codtarifa' => $tarifas->codtarifa,// integer,
           'identificacion_recibe' => $request->identificacion_recibe,// numeric,
           'nombres_recibe' => $request->nombres,// character varying(515),
           'apellidos_recibe' => $request->apellidos,// character varying(515),
           'celular_recibe' => $request->celular,// character varying(515),
           'valor' => $request->valor,// numeric,
           'estado' => 2,// smallint,
           'codsucursal' => $request->codsucursal,
           'codigo' => rand (10000000, 99999999),
           'fecha_transaccion' => Carbon::now(),// timestamp without time zone,
           'valor_recibido' => $request->valor_recibido,
           'cod_sucursal_envia' => $sucursal->codsucursal,
           'cod_empleado_envia' => $empleado->codempleado,
           'ocupacion' => $request->ocupacion,
           'origen_ingresos' => $request->origen_ingresos,
           'valor_tarifa' => $costo
       ]));
        
        
        $valor_comision = $tarifa->costo_envia;
        $valor_comision = blank($tarifa->porcentaje_envia) ? $valor_comision : $valor_comision+$movimiento->valor*$tarifa->porcentaje_envia/100;
        $sucursal->movimientos_comision()->attach($movimiento,[
            'codtarifa'=>$tarifa->codtarifa,
            'tipo_movimiento' => 1,
            'valor_real' => $movimiento->valor,
            'valor_comision' => $valor_comision,
            'fecha_movimiento' => Carbon::now(),
            'estado' => 1,
            'saldo_inicial' => $sucursal->saldo,
            'saldo_final' => $sucursal->saldo+$movimiento->valor+$costo,
            'estado_pago' => 1
        ]);
        $saldo = $sucursal->saldo+$movimiento->valor+$costo;
        $sucursal->update([
            'saldo' => $saldo
        ]);
        $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        ->withData( array( 'user' => '00486812691','key'=>'0f0c250fcdd790013eb7b41ad611b5707e022201', 'num'=>'57'.str_replace(' ','',$cliente->celular),'sms'=>'GIROS AFA, te confirma envio por valor de $ '.number_format($movimiento->valor).'. Codigo de retiro '.$movimiento->codigo.' Gracias por utilizar nuestros servicios. Vigencia: '.Carbon::parse(Carbon::now())->addDays(30)->format(trans('general.format_date'))))
        ->post();
        $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        ->withData( array( 'user' => '00486812691','key'=>'0f0c250fcdd790013eb7b41ad611b5707e022201', 'num'=>'57'.str_replace(' ','',$celular),'sms'=>'GIROS AFA, te confirma envio por valor de $ '.number_format($movimiento->valor).'. Codigo de retiro '.$movimiento->codigo.' Gracias por utilizar nuestros servicios. Vigencia: '.Carbon::parse(Carbon::now())->addDays(30)->format(trans('general.format_date'))))
        ->post();
        $movimiento->update([
            'sms' => $response,
        ]);
        session(['last_m' =>  ($movimiento->codmovimiento)]);
        return redirect()->route('transacciones')->with([
            'm' => ($movimiento->codmovimiento)
        ]);
        exit();

    }
    

    public function enviarGiroBanco(Request $request)
    {
        $empleado = Empleado::find(\Auth::user()->codempleado);
        //dd(\Auth::user());
        $sucursal = Sucursal::find(session('sucursal')->codsucursal);
        $consignacion = new Consignaciones([
            'codconvenio' => $request->codconvenio,// integer,
            'tipo_cuenta' => $request->tipo_cuenta,// integer,
            'codtarifa' => $request->codtarifa,
            'numero_cuenta' => $request->numero_cuenta,// integer,
            'numero_referencia'=> $request->numero_referencia, //integer
            'numero_convenio'=> $request->numero_convenio, //integer
            'identificacion_envia' => $request->identificacion_envia,
            'telefono_envia' => $request->telefono_envia,
            'nombre_envia' => $request->nombre_envia,
            'identificacion_recibe' => $request->identificacion_recibe,
            'telefono_recibe' => $request->telefono_recibe,
            'nombre_recibe' => $request->nombre_recibe,
            'valor' => $request->valor_giro,
            'valor_recibido' => $request->valor_recibido,
            'valor_tarifa' => $request->valor_tarifa,
            'fecha' => Carbon::now(),
            'estado' => 1,
            'codsucursal' => $sucursal->codsucursal,
            'codempleado' => $empleado->codempleado,
            'sms' => ''
        ]);   
        $tarifa = DB::table('tarifas_convenios')->where('codtarifa','=',$consignacion->codtarifa)->first();
        $valor_comision = $tarifa->costo_envia;
        $valor_comision = blank($tarifa->porcentaje_envia) ? $valor_comision : $valor_comision+$consignacion->valor*$tarifa->porcentaje_envia/100;
        $consignacion->save();
        $sucursal->movimientos_consignacion()->attach($consignacion,[
            'codtarifa'=>$consignacion->codtarifa,
            'codconsignacion' => $consignacion->codconsignacion,
            'tipo_movimiento' => 3,
            'valor_real' => $consignacion->valor,
            'valor_comision' => $valor_comision,
            'fecha_movimiento' => Carbon::now(),
            'estado' => 1,
            'saldo_inicial' => $sucursal->saldo,
            'saldo_final' => $sucursal->saldo+$consignacion->valor+$consignacion->valor_tarifa,
            'estado_pago' => 1
        ]);
        $saldo = $sucursal->saldo+$consignacion->valor+$consignacion->valor_tarifa;
        $sucursal->update([
            'saldo' => $saldo
        ]);
        $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        ->withData( array( 'user' => '00486812691','key'=>'68c4bbbd0b32493cf602a7b953ce016312f356fa', 'num'=>'57'.str_replace(' ','',$request->telefono_envia),'sms'=>'GIROS AFA, te confirma consignacion por valor de $ '.number_format($consignacion->valor).'. Gracias por utilizar nuestros servicios. Vigencia: '.Carbon::parse(Carbon::now())->addDays(30)->format(trans('general.format_date'))))
        ->post();
        $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        ->withData( array( 'user' => '00486812691','key'=>'68c4bbbd0b32493cf602a7b953ce016312f356fa', 'num'=>'57'.str_replace(' ','',$request->telefono_recibe),'sms'=>'GIROS AFA, te confirma consignacion por valor de $ '.number_format($consignacion->valor).'. Gracias por utilizar nuestros servicios. Vigencia: '.Carbon::parse(Carbon::now())->addDays(30)->format(trans('general.format_date'))))
        ->post();
        return response()->json([
            'codconsignacion' => $consignacion->codconsignacion,
            'saldo' => number_format($saldo)
        ]);
        exit();

        
    }

    public function pagarConvenio(Request $request)
    {
        
        $empleado = Empleado::find(\Auth::user()->codempleado);
        $sucursal = Sucursal::find(session('sucursal')->codsucursal);
        $convenio = Convenio::find($request->codconvenio);
        $campos = DB::table('banco_datos AS bd')
        ->join('rel_convenio_banco as r','r.codbanco','=','bd.codbanco')
        ->where('r.estado','=',1)
        ->where('bd.estado','=',1)
        ->where('codconvenio','=', $request->codconvenio)->orderBy('r.codrel')->get();
        $campoText = '';
        $campoArray = [];
        foreach ($campos as $cp) {
            $campoArray[$cp->nombre] = ($request["field_".$cp->codbanco]);
            $campoText.=$cp->nombre.($request["field_".$cp->codbanco]).' - ';
        }
        $pago = new Pagos([
            'codconvenio' => $convenio->codconvenio,// integer,
            'campos' => $campoText,// character varying(100),
            'campos_js' => json_encode($campoArray),// character varying(100),
            'valor' => $convenio->codcategoria == 4 ? $request->valor : $request->valor_convenio,// numeric,
            'fecha' => Carbon::now(),
            'estado' => 1,// smallint,
            'estado_pago' => 2,// smallint,
            'codsucursal' => $sucursal->codsucursal,
            'codempleado' => $empleado->codempleado,
            'tipo' => 2,
            'codtarifa' => $request->codtarifa,
            'valor_tarifa' => $request->valor_tarifa,
            'valor_recibido' => $request->valor_recibido
        ]);
        if($convenio->codcategoria == 4){
            $tarifa = DB::table('tarifas_tarjetas')->where('codtarifa','=',$request->codtarifa)->first();
        }
        $request->valor_tarifa = blank($request->valor_tarifa) ? 0 : $request->valor_tarifa;
        $pago->save();
        $sucursal->movimientos_pagos()->attach($pago,[
            'tipo_movimiento' => 5,
            'valor_real' => $pago->valor,
            'valor_comision' => $convenio->codcategoria == 4 ? $tarifa->costo_envia : $convenio->utilidad,
            'fecha_movimiento' => Carbon::now(),
            'estado' => 1,
            'saldo_inicial' => $sucursal->saldo,
            'saldo_final' => $sucursal->saldo+$pago->valor+$request->valor_tarifa,
            'estado_pago' => 1
        ]);
        $saldo = $sucursal->saldo+$pago->valor+$request->valor_tarifa;
        $sucursal->update([
            'saldo' => $saldo
        ]);
        $objDemo = new \stdClass();
        $objDemo->demo_one = $convenio->nombre;
        $objDemo->demo_two = $pago->valor;
        $objDemo->demo_tree = $sucursal->nombres;
        $objDemo->sender = 'Plataforma de pagos - afainversiones';
        $objDemo->receiver = 'Auxiliar de pagos';
        set_time_limit(300);
        //Mail::to("asistentepagosafa@gmail.com")->send(new DemoEmail($objDemo));
        
        return response()->json([
            'estado' => 1,
            'codpago' => $pago->codpago,
            'saldo' => number_format($sucursal->saldo)
        ]);
        dd(json_encode($campoArray), $campoText);
    }


    

    public function registrarRecarga(Request $request)
    {
        
        $request_date=date('YmdHis.000');
        $api = new Winred();
        $empleado = Empleado::find(\Auth::user()->codempleado);

        $sucursal = Sucursal::find(session('sucursal')->codsucursal);

        $recargaPaquete = DB::table('recargas_paquetes AS a')->where('codigo','=', $request->operador)->first();
        //$recargaPaquete = RecargaPaquete::find($request->operador);
        $valorRecarga = $request->valor;
        $tipo = 1;
        $datos = '';
        
        if(!blank($request->snr)){
            $tipo = 3;
            $transaccion = $api->snr('4',$request_date,'21','0',"$request->matricula","$request->numero","$request->correo","$request->operador",true);
            $arrayTemp = [
                "matricula" => $request->matricula,
                "celular" => $request->numero,
                "correo" => $request->correo,
                "oficina"=> $request->operador
                ];
            $request->numero = $request->matricula;    
            $datos = json_encode($arrayTemp);
        }else{
            if($recargaPaquete->tipo==4){
                $tipo = 4;
                $transaccion = $api->pin('4',$request_date, $request->operador,'0');
            }else{
                if($recargaPaquete->tipo==2){
                    $valorRecarga='0';
                    $tipo = 2;
                }
                if($recargaPaquete->codigo==18){
                    $transaccion = $api->sportBet('4',$request_date,'18',"$request->valor","$request->identificacion","$request->numero",true);
                    // cambio
                    $datos = json_encode(['cedula'=>$request->identificacion]);
                }else{
                    $transaccion = $api->topUp('2',$request_date, $request->operador,$valorRecarga,$request->numero);
                }
            }
        }
        
        $response = json_decode($transaccion, true);
        if($response==null){
            $response["result"]["success"] = false;
            $response["result"]["message"] = 'No fue posible comunicarse con el proveedor';
        }
        $recarga = new Recarga([
            'codrecpaq' => $request->operador,
            'numero' => $request->numero,
            'valor' => $request->valor,
            'fecha' => Carbon::now(),
            'fecha_curl' => $request_date,
            'estado' => $response["result"]["success"] ? 1 : 2,
            'response' => ''.$transaccion,
            'mensaje' => $response["result"]["message"],
            'codsucursal' => $sucursal->codsucursal,
            'codempleado' => $empleado->codempleado,
            'tipo' => $tipo,
            'datos' => $datos
        ]);
        $recarga->save();
        if($recarga->estado==1){
            $comision = !blank($request->snr) ? 300 : ($recargaPaquete->codigo==18 || $recargaPaquete->tipo==4 ? $recarga->valor*1.5/100 : $recarga->valor*5/100);
            $sucursal->movimientos_recargas()->attach($recarga,[
                'codrecarga' => $recarga->codrecarga,
                'tipo_movimiento' => 4,
                'valor_real' => $recarga->valor,
                'valor_comision' => $comision,
                'fecha_movimiento' => Carbon::now(),
                'estado' => 1,
                'saldo_inicial' => $sucursal->saldo,
                'saldo_final' => $sucursal->saldo+$recarga->valor,
                'estado_pago' => 1
            ]);
            $saldo = $sucursal->saldo+$recarga->valor;
            $sucursal->update([
                'saldo' => $saldo
            ]);
        }else{
            $sucursal->movimientos_recargas()->attach($recarga,[
                'codrecarga' => $recarga->codrecarga,
                'tipo_movimiento' => 4,
                'valor_real' => $recarga->valor,
                'valor_comision' => 0,
                'fecha_movimiento' => Carbon::now(),
                'estado' => 2,
                'saldo_inicial' => $sucursal->saldo,
                'saldo_final' => $sucursal->saldo,
                'estado_pago' => 1
            ]);
        }
        return response()->json([
            'estado' => $response["result"]["success"] ? 1 : 2,
            'codrecarga' => $recarga->codrecarga,
            'mensaje' => $response["result"]["message"],
            'saldo' => number_format($sucursal->saldo)
        ]);
        exit();
        
        
        
        dd($file,$request->all());
    }


    public function soatRecarga(Request $request)
    {
        
        $request_date=date('YmdHis.000');
        $api = new Winred();
        $empleado = Empleado::find(\Auth::user()->codempleado);

        $sucursal = Sucursal::find(session('sucursal')->codsucursal);
        $transaccion = $api->soat(3,$request_date,'25','0',$request->placa,$request->tipo_identificacion,$request->identificacion,$request->tipo_vehiculo,$request->celular,$request->correo,true);
        $response = json_decode($transaccion, true);
        if($response==null){
            $response["result"]["success"] = false;
            $response["result"]["message"] = 'No fue posible comunicarse con el proveedor';
        }
        $soat = new Soat([
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'numero_placa' => $request->placa,
            'tipo_identificacion' => $request->tipo_identificacion,
            'identificacion' => $request->identificacion,
            'nro_celular' => $request->celular,
            'correo' => $request->correo,
            'fecha' => Carbon::now(),
            'fecha_curl' => $request_date,
            'response' => $transaccion,
            'valor' => $request->valor,
            'estado' => $response["result"]["success"] ? 1 : 2,
            'codsucursal' => $sucursal->codsucursal,
            'codempleado' => $empleado->codempleado,
            'mensaje' => $response["result"]["message"],
        ]);
        $soat->save();
        if($soat->estado==1){
            $response = json_decode($soat->response, true);
            $data = $response["data"];
            $sucursal->movimientos_soat()->attach($soat,[
                'codsoat' => $soat->codsoat,
                'tipo_movimiento' => 7,
                'valor_real' => $soat->valor,
                'valor_comision' => $data["insurance_amount"]*2.5/100,
                'fecha_movimiento' => Carbon::now(),
                'estado' => 1,
                'saldo_inicial' => $sucursal->saldo,
                'saldo_final' => $sucursal->saldo+$soat->valor,
                'estado_pago' => 1
            ]);
            $saldo = $sucursal->saldo+$soat->valor;
            $sucursal->update([
                'saldo' => $saldo
            ]);
        }else{
            $sucursal->movimientos_soat()->attach($soat,[
                'codsoat' => $soat->codsoat,
                'tipo_movimiento' => 7,
                'valor_real' => $soat->valor,
                'valor_comision' => 0,
                'fecha_movimiento' => Carbon::now(),
                'estado' => 2,
                'saldo_inicial' => $sucursal->saldo,
                'saldo_final' => $sucursal->saldo,
                'estado_pago' => 1
            ]);
        }
        return response()->json([
            'estado' => $response["result"]["success"] ? 1 : 2,
            'codsoat' => $soat->codsoat,
            'response' => $transaccion,
            'mensaje' => $response["result"]["message"],
            'saldo' => number_format($sucursal->saldo)
        ]);
        exit();
        exit();
        
        
        
        
        dd($file,$request->all());
    }
    
    public function enviarMensaje(Request $request)
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->demo_tree = 'Demo Two Value';
        $objDemo->sender = 'info@afainversiones.com';
        $objDemo->receiver = 'Bayron Tarazona';
        set_time_limit(300);
        //Mail::to("bayronthz@gmail.com")->send(new DemoEmail($objDemo));
        exit();
        $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        ->withData( array( 'user' => '00486812691','key'=>'68c4bbbd0b32493cf602a7b953ce016312f356fa', 'num'=>'57'.$request->celular,'sms'=>$request->mensaje." Gracias por preferirnos! facebook.com/aloedesignbmga"))
        ->post();
        
        dd($response);
        session(['last_m' =>  ($movimiento->codmovimiento)]);
        return redirect()->route('transacciones')->with([
            'm' => ($movimiento->codmovimiento)
        ]);
        exit();
    }



    public function retirarGiro(Request $request)

    {
        $movimiento = Movimiento::find($request->codgiro);
        $empleado = Empleado::find(\Auth::user()->codempleado);
        $tarifa = Tarifas::find($movimiento->codtarifa);
        $valor_comision = $tarifa->costo_paga;
        $valor_comision = blank($tarifa->porcentaje_paga) ? $valor_comision : $valor_comision+$movimiento->valor*$tarifa->porcentaje_paga/100;
        //dd(\Auth::user());
        
        $sucursal = Sucursal::find(session('sucursal')->codsucursal);
        $dataUpdate = [
            'cod_sucursal_retira' => $sucursal->codsucursal,
            'cod_empleado_retira' => $empleado->codempleado,
            'fecha_retiro' => Carbon::now(),
            'estado' => 1
        ];
        
        $movimiento->update($dataUpdate);
        $sucursal->movimientos_comision()->attach($movimiento,[
            'codtarifa'=>$tarifa->codtarifa,
            'tipo_movimiento' => 2,
            'valor_real' => $movimiento->valor,
            'valor_comision' => $valor_comision,
            'fecha_movimiento' => Carbon::now(),
            'estado' => 1,
            'saldo_inicial' => $sucursal->saldo,
            'saldo_final' => $sucursal->saldo-$movimiento->valor,
            'estado_pago' => 1
        ]);
        $saldo = $sucursal->saldo-$movimiento->valor;
        $sucursal->update([
            'saldo' => $saldo
        ]);
        
        // $response = Curl::to('https://www.cellvoz.com/APIlib/SMSAPI.php')
        // ->withData( array( 'user' => '00486812691','key'=>'68c4bbbd0b32493cf602a7b953ce016312f356fa', 'num'=>'57'.str_replace(' ','',$request->celular),'sms'=>$producto->nombre.': 1 Copia el PIN '.$inventario->pin.' 2 Conectate a la wi-fi del vecino  3 Ingresa a la dirección digital.com, pega tu PIN y click en navegar.'))
        // ->post();
        
        session(['last_m' =>  bcrypt($movimiento->codmovimiento)]);
        return response()->json([
            'resultado' => 1,
            'formulario' => view('principal.transacciones')->render(),
            'detalle' => view('principal.transacciones-detalle')->render()
        ]);
        exit();
        return redirect()->route('transacciones', [
            'm' => bcrypt($movimiento->codmovimiento)
        ]);
        exit();

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
        return view('usuarios.index',[
            'mensaje' => "",
            'listEstudiantes'=> DB::table('usuarios')->where('email',1)->select('*')->get(),
            'listDocentes'=> []
        ]);
    }

    public function abrirFormulario(Request $request){
        if(blank($request->codcliente)){
            $cliente= new Cliente();
        }else{
            $cliente = Cliente::find($request->codcliente);
        }
        return response()->json([
            'formulario' => view('usuarios.partials.form')->with([
                'cliente' => $cliente,
            ])->render()
        ]);
        
    }

    public function abrirFormularioTransacciones(Request $request){
        if(blank($request->codcliente)){
            $cliente= new Cliente();
        }else{
            $cliente = Cliente::find($request->codcliente);
        }
        return response()->json([
            'formulario' => view('usuarios.partials.form-transacciones')->with([
                'cliente' => $cliente,
            ])->render()
        ]);
        
    }

    
    public function eliminar(Request $request){
        
        $cliente = Cliente::destroy($request->codcliente);
        
        return response()->json([
            'formulario' => $cliente
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
        return redirect()->route('clientes.index', [
            'm' => base64_encode($mensaje),
            'd' => $error
        ]);
        exit();
        
        
        
        dd($file,$request->all());
    }


    public function revertirGiro(Request $request)
    {
        $movimiento = Movimiento::find($request->codgiro);
        $cliente = Cliente::find($movimiento->codcliente_envia);
        $movimiento->update([
            'codcliente_recibe' => $movimiento->codcliente_envia,
            'identificacion_recibe' => $cliente->identificacion,
            'nombres_recibe' => $cliente->nombres,
            'apellidos_recibe' => $cliente->apellidos,
            'celular' => $cliente->celular,
        ]);
        return response()->json([
            'mensaje' => 'Giro revertido con éxito'
        ]);
    }

    /**
     *
     * @tutorial Method Description: carga la vista de crear pagina
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/03/2018}
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function crearCliente(Request $request)
    {
        $dataCliente=$request->all();
        unset($dataCliente["_token"]);
        unset($dataCliente["celular_confirmar"]);
        
        if(blank($request->codcliente)){
            $cliente = new Cliente($dataCliente);
            $cliente->save();
            DB::table('movimientos AS m')->where('identificacion_recibe','=',$cliente->identificacion)->where('estado','=','2')->update(['codcliente_recibe' => $cliente->codcliente]);
            $request->mensaje = "Asignatura guardada con éxito";
        }else{
            $cliente = Cliente::find($request->codcliente);
            $cliente->update($dataCliente);
        }
        return redirect()->route('clientes.index', [
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


    /**
     *
     * @tutorial Method Description: carga la vista de crear pagina
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/03/2018}
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function crearClienteTransacciones(Request $request)
    {
        $dataCliente=$request->all();
        unset($dataCliente["_token"]);
        unset($dataCliente["celular_confirmar"]);
        $dataCliente['fecha_expedicion'] = Carbon::parse(str_replace('*','-',$dataCliente['fecha_expedicion']))->format('Y-m-d');
        $dataCliente['fecha_nacimiento'] = Carbon::parse(str_replace('*','-',$dataCliente['fecha_nacimiento']))->format('Y-m-d');
        if(blank($request->codcliente)){
            $cliente = new Cliente($dataCliente);
            $cliente->save();
            DB::table('movimientos AS m')->where('identificacion_recibe','=',$cliente->identificacion)->where('estado','=','2')->update(['codcliente_recibe' => $cliente->codcliente]);
            $request->mensaje = "Asignatura guardada con éxito";
        }else{
            $cliente = Cliente::find($request->codcliente);
            $cliente->update($dataCliente);
        }
        
        
        return redirect()->route('transacciones', [
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
    public function detalleEstudiante(Request $request)
    {
        $estudiante = Estudiante::find(decrypt($request->codestudiante));
        $grado = $estudiante->cursoActivo();
        return view('estudiantes.estudiante-detalle',[
            "estudiante" => $estudiante,
            "grado" => $grado,
            "observaciones" => $estudiante->observaciones
        ]);
    }

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

