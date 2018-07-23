<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

use App\Empleado;
use App\Area;
use App\User;
use App\Pais;
use App\Ciudad;
use App\Zona;
use App\TipoEnvio;
use App\TipoProducto;
use App\EstadoBitacora;
use App\EstadoEntrega;
use App\EstadoEnvio;


/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    
    public function index()
    {
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);

        $registros = $this->registrosDiarios($empleado->id, $area->nombre);
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'registros'=>$registros];
        return view('home',array('datos'=>$datos));
    }

    public function reporte(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $fecha = Carbon::now()->toDateString();
        $user = User::all()->where('id', $empleado->user_id)->first();
        $datos_empleado = ['id'=>$empleado->id, 'nombre'=>$user->name];

        $registros = $this->registrosDiarios($empleado->id, $area->nombre);
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'registros'=>$registros, 'empleado'=>$datos_empleado, 'fecha'=>$fecha];

        $view = view('reportes.reporte', array('datos'=>$datos));
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('reporte'.str_replace(' ', '', $datos_empleado['nombre']).$fecha.'.pdf');

    }

    public function registrosDiarios($empleado_id, $nombre){
        $fecha = Carbon::now()->toDateString();
        $registros = [];

        if($nombre == 'Clasificacion'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            D.apellido_paterno as destinatario_apellido_paterno, D.apellido_materno as destinatario_apellido_materno, D.nombre as destinatario_nombre, D.telefono as destinatario_telefono, D.calle_avenida as destinatario_calle_avenida, D.edificio_nro as destinatario_edificio_nro, D.pais_id as destinatario_pais_id, D.ciudad_id as destinatario_ciudad_id, D.zona_id as destinatario_zona_id, 
                                            E.codigo_envio as envio_codigo, DATE(E.fecha_hora_envio) as envio_fecha, E.peso as envio_peso, E.pais_id as envio_pais_id, E.tipo_envio_id as envio_tipo_envio_id, E.tipo_producto_id as envio_tipo_producto_id, E.estado_envio_id as envio_estado_id
                                     FROM destinatarios D, envios E, bitacora_de_registros B
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = D.registro_id and B.registro_id = E.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;

                $pais = Pais::find($registro->destinatario_pais_id);
                $registro->destinatario_pais_nombre = $pais->nombre;
                
                $ciudad = Ciudad::find($registro->destinatario_ciudad_id);
                $registro->destinatario_ciudad_nombre = $ciudad->nombre;

                $zona = Zona::find($registro->destinatario_zona_id);
                $registro->destinatario_zona_nombre = $zona->nombre;
                
                $pais = Pais::find($registro->envio_pais_id);
                $registro->envio_pais_nombre = $pais->nombre.' - '.$pais->oficina_iata;

                $tipo_envio = TipoEnvio::find($registro->envio_tipo_envio_id);
                $registro->envio_tipo_envio_nombre = $tipo_envio->tipo_envio;

                $tipo_producto = TipoProducto::find($registro->envio_tipo_producto_id);
                $registro->envio_tipo_producto_nombre = $tipo_producto->tipo_producto;

                $estado_envio = EstadoEnvio::find($registro->envio_estado_id);
                $registro->envio_estado_nombre = $estado_envio->estado_envio;
            }
        }elseif($nombre == 'Almacenaje'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            A.ubicacion as almacenaje_ubicacion,
                                            E.codigo_envio as envio_codigo
                                     FROM almacenajes A, bitacora_de_registros B, envios E
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = A.registro_id and B.registro_id = E.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;
            }
        }elseif($nombre == 'Entrega'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            ET.monto_a_pagar as entrega_monto_a_pagar, ET.estado_entrega_id as entrega_estado_id, 
                                            F.id as factura_nro, F.esFactura as factura_es_factura, F.nit_factura as factura_nit, F.nombre_factura as factura_nombre, F.monto_factura as factura_monto,
                                            EV.codigo_envio as envio_codigo
                                     FROM entregas ET, facturas F, envios EV, bitacora_de_registros B
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = ET.registro_id and B.registro_id = F.registro_id and B.registro_id = EV.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;

                $estado_entrega = EstadoEntrega::find($registro->entrega_estado_id);
                $registro->entrega_estado_nombre = $estado_entrega->estado_entrega;
            }
        }

        return $registros;
    }
}