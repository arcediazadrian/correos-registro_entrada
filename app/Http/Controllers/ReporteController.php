<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DB;

use App\Empleado;
use App\Area;
use App\Rango;
use App\User;
use App\Pais;
use App\Ciudad;
use App\Zona;
use App\TipoServicio;
use App\TipoProducto;
use App\EstadoBitacora;
use App\EstadoEntrega;
use App\EstadoEnvio;

class ReporteController extends Controller
{
    
    /*
    * index: muestra la pagina general de los reportes donde se ven los registros diarios y el boton de generar reporte
    */
    public function index()
    {
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        $registros = $this->datosReporte($empleado->id, $area->nombre, $rango->nombre);
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'registros'=>$registros];
        return view('reportes.reporte',array('datos'=>$datos));
    }

    /*
    * reporte: genera el reporte en formato pdf dependiendo del area y rango(cargos)
    */
    public function reporte(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);
        $fecha = Carbon::now()->toDateString();
        $formato_fecha = DateTime::createFromFormat("Y-m-d", $fecha);
        $dia = $formato_fecha->format("d");
        $mes = $formato_fecha->format("m");
        $ano = $formato_fecha->format("Y");
        $fecha = ['fecha'=>$fecha, 'dia'=>$dia, 'mes'=>$mes, 'ano'=>$ano];
        $user = User::all()->where('id', $empleado->user_id)->first();
        $datos_empleado = ['id'=>$empleado->id, 'nombre'=>$user->name];
        $ciudad = Ciudad::find($empleado->ciudad_id);
        

        $registros = $this->datosReporte($empleado->id, $area->nombre, $rango->nombre);
 
        $datos = ['area'=>$area->nombre, 'rango'=>$rango->nombre, 'registros'=>$registros, 'empleado'=>$datos_empleado, 'fecha'=>$fecha, 'ciudad'=>$ciudad->nombre];

        $view = view('reportes.reportepdf', array('datos'=>$datos));
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('reporte'.str_replace(' ', '', $datos_empleado['nombre']).$fecha['fecha'].'.pdf');

    }

    /*
    * datosReporte: genera los datos de los registros para el reporte dependiendo del area y rango(cargo)
    */
    public function datosReporte($empleado_id, $area, $rango){
        $fecha = Carbon::now()->toDateString();
        $registros = [];

        if($area == 'Clasificacion' && $rango == 'Empleado'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            D.apellido_paterno as destinatario_apellido_paterno, D.apellido_materno as destinatario_apellido_materno, D.nombre as destinatario_nombre, D.telefono as destinatario_telefono, D.calle_avenida as destinatario_calle_avenida, D.edificio_nro as destinatario_edificio_nro, D.pais_id as destinatario_pais_id, D.ciudad_id as destinatario_ciudad_id, D.zona_id as destinatario_zona_id, 
                                            E.codigo_envio as envio_codigo, DATE(E.fecha_hora_envio) as envio_fecha, E.peso as envio_peso, E.pais_id as envio_pais_id, E.tipo_servicio_id as envio_tipo_servicio_id, E.tipo_producto_id as envio_tipo_producto_id, E.estado_envio_id as envio_estado_id
                                     FROM destinatarios D, envios E, bitacora_de_registros B
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = D.registro_id and B.registro_id = E.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                /*
                * Ya que en las tablas solo tenemos los ids de las parametricas, necesitamos generar los nombres de estas
                * para que se muestren en el reporte
                */
                
                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;
                if($estado_bitacora->area_id != null){
                    $area = Area::find($estado_bitacora->area_id);
                    $registro->bitacora_estado_nombre = $registro->bitacora_estado_nombre.': '.$area->nombre;
                }

                $pais = Pais::find($registro->destinatario_pais_id);
                $registro->destinatario_pais_nombre = $pais->nombre;
                
                $ciudad = Ciudad::find($registro->destinatario_ciudad_id);
                $registro->destinatario_ciudad_nombre = $ciudad->nombre;
                
                $zona = Zona::find($registro->destinatario_zona_id);
                if($zona != null){
                    $registro->destinatario_zona_nombre = $zona->nombre;
                }else{
                    $registro->destinatario_zona_nombre = '';
                }
                
                $pais = Pais::find($registro->envio_pais_id);
                if($pais != null){
                    $registro->envio_pais_nombre = $pais->nombre.' - '.$pais->oficina_iata;
                }else{
                    $registro->envio_pais_nombre = '';
                }
                

                $tipo_servicio = TipoServicio::find($registro->envio_tipo_servicio_id);
                $registro->envio_tipo_servicio_nombre = $tipo_servicio->tipo_servicio;

                $tipo_producto = TipoProducto::find($registro->envio_tipo_producto_id);
                $registro->envio_tipo_producto_nombre = $tipo_producto->tipo_producto;

                $estado_envio = EstadoEnvio::find($registro->envio_estado_id);
                $registro->envio_estado_nombre = $estado_envio->estado_envio;
            }
        }elseif($area == 'Clasificacion' && $rango == 'Supervisor'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            E.codigo_envio as envio_codigo
                                     FROM envios E, bitacora_de_registros B
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = E.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                /*
                * Ya que en las tablas solo tenemos los ids de las parametricas, necesitamos generar los nombres de estas
                * para que se muestren en el reporte
                */

                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;
                if($estado_bitacora->area_id != null){
                    $area = Area::find($estado_bitacora->area_id);
                    $registro->bitacora_estado_nombre = $registro->bitacora_estado_nombre.': '.$area->nombre;
                }
            }
        }elseif($area == 'Almacenaje' && $rango == 'Empleado'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            A.ubicacion as almacenaje_ubicacion,
                                            E.codigo_envio as envio_codigo
                                     FROM almacenajes A, bitacora_de_registros B, envios E
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = A.registro_id and B.registro_id = E.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                /*
                * Ya que en las tablas solo tenemos los ids de las parametricas, necesitamos generar los nombres de estas
                * para que se muestren en el reporte
                */

                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;
                if($estado_bitacora->area_id != null){
                    $area = Area::find($estado_bitacora->area_id);
                    $registro->bitacora_estado_nombre = $registro->bitacora_estado_nombre.': '.$area->nombre;
                }
            }
        }elseif($area == 'Entrega' && $rango == 'Empleado'){
            $registros = DB::select('SELECT B.registro_id as registro_id, cast(B.created_at as time) as bitacora_hora, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, 
                                            ET.monto_a_pagar as entrega_monto_a_pagar, ET.estado_entrega_id as entrega_estado_id, 
                                            F.nro_factura as factura_nro, F.esFactura as factura_es_factura, F.nit_factura as factura_nit, F.nombre_factura as factura_nombre, F.monto_factura as factura_monto, F.esManual as factura_es_manual,
                                            EV.codigo_envio as envio_codigo
                                     FROM entregas ET, facturas F, envios EV, bitacora_de_registros B
                                     WHERE B.empleado_id = '.$empleado_id.' and B.registro_id = ET.registro_id and B.registro_id = F.registro_id and B.registro_id = EV.registro_id and DATE(B.created_at) = "'.$fecha.'"');

            foreach($registros as $registro) {
                /*
                * Ya que en las tablas solo tenemos los ids de las parametricas, necesitamos generar los nombres de estas
                * para que se muestren en el reporte
                */
                
                $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                $registro->bitacora_estado_nombre = $estado_bitacora->nombre;
                if($estado_bitacora->area_id != null){
                    $area = Area::find($estado_bitacora->area_id);
                    $registro->bitacora_estado_nombre = $registro->bitacora_estado_nombre.': '.$area->nombre;
                }

                $estado_entrega = EstadoEntrega::find($registro->entrega_estado_id);
                $registro->entrega_estado_nombre = $estado_entrega->estado_entrega;
            }
        }

        return $registros;
    }
}