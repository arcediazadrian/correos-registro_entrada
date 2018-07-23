<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use Carbon\Carbon;

use App\Registro;
use App\Empleado;
use App\Area;
use App\Pais;
use App\Ciudad;
use App\Zona;
use App\TipoEnvio;
use App\TipoProducto;
use App\EstadoEnvio;
use App\Destinatario;
use App\Envio;
use App\BitacoraDeRegistro;
use App\Almacenaje;
use App\Entrega;
use App\EstadoEntrega;
use App\Factura;


class RegistroController extends Controller
{
    public function registro(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);

        if($area->nombre == 'Entrega'){
            return redirect('registro/buscar');
        }elseif($area->nombre == 'Clasificacion' || $area->nombre == 'Almacenaje'){
            return redirect('registro/registroPaquete');
        }else{
            return "AREA NOT FOUND";
        }
    }
    
    public function registroEntrega(Request $request){
        $envio = Envio::all()->where('codigo_envio',$request->input('codigo_envio'))->first();
        
        if($envio == null){
            return redirect('registro/buscar')->with('error', 'Paquete no encontrado');
        }else{
            $entrega = Entrega::all()->where('registro_id', $envio->registro_id)->first();
            $factura = Factura::all()->where('registro_id', $envio->registro_id)->first();
            if($entrega == null && $factura == null){
                $registro_id = $envio->registro_id;
                $almacenaje = Almacenaje::where('registro_id', $registro_id)->first();
                if($almacenaje == null){
                    return redirect('registro/buscar')->with('error', 'No se ha creado un registro de Almacenamiento con este codigo de envio');
                }else{
                    $ubicacion = 'Ubicacion en el almacen: '.$almacenaje->ubicacion;
        
                    $id = auth()->user()->id;
                    $empleado = Empleado::all()->where('user_id',$id)->first();
                    $area = Area::find($empleado->area_id);
                    
                    $datos = ['empleado'=>$empleado, 'area'=>$area, 'codigo_envio'=>$envio->codigo_envio, 'ubicacion'=>$ubicacion];
                    $datos = $this->generarDatos($datos, $datos['area']->nombre);
                    
                    return view('registros.registrar', array('datos'=>$datos));   
                }
            }else{
                return redirect('registro/buscar')->with('error', 'Ya se creo un registro de Entrega con esta codigo de envio');
            }
        }
        
    }

    public function registroPaquete(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        
        $datos = ['empleado'=>$empleado, 'area'=>$area];
        $datos = $this->generarDatos($datos, $datos['area']->nombre);
        return view('registros.registrar', array('datos'=>$datos));   
    }

    public function guardar(Request $request){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);

        $validacion = $this->generarDatosValidacion($area->nombre);

        $this->validate($request, $validacion['conditions'], $validacion['errormsgs']);
                
        $datos = $this->guardarDatos($request, $area->nombre, $empleado->id);
        if($datos['error'] == 1){
            return redirect('registro/registroPaquete')->with('error', 'Ya se creo un registro de '.$area->nombre.' con este codigo de envio');
        }else{
            $datos['empleado'] = $empleado;
            $datos['area'] = $area;
    
            return view('registros.guardar', array('datos'=>$datos));
        }
    }

    public function buscar(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $datos = ['empleado'=>$empleado, 'area'=>$area];

        return view('registros.buscar',array('datos'=>$datos));

    }

    public function generarDatos($datos, $nombre){
        if($nombre == 'Clasificacion'){
            $datos['paises'] = $paises = $this->getPaises();
            $datos['iatas'] = $this->getIata();
            $datos['ciudades'] = $this->getCiudades();
            $datos['zonas'] = $this->getZonas();
            $datos['tipos_envio'] = $this->getTiposEnvio();
            $datos['tipos_producto'] = $this->getTiposProducto();
            $datos['estados_envio'] = $this->getEstadosEnvio();
            $datos['fecha'] = Carbon::now()->toDateString();
        }elseif($nombre == 'Entrega'){
            $datos['estados_entrega'] = $this->getEstadosEntrega();
        }else{
            
        }

        return $datos;
    }

    public function generarDatosValidacion($nombre){
        $datos['errormsgs'] = ['required'=>'El campo :attribute es obligatorio', 
                               'unique'=>':attribute debe ser unico',
                               'exists'=>':attribute no existe',
                            ];

        if($nombre == 'Clasificacion'){
            $datos['conditions'] = [
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'nombre' => 'required',
                'calle_avenida' => 'required',
                'edificio_nro' => 'required',
                'pais_id_dest' => 'required',
                'ciudad_id' => 'required',
                'zona_id' => 'required',
                'codigo_envio' => 'required|unique:envios',
                'fecha_hora_envio' => 'required',
                'peso' => 'required',
                'pais_id_env' => 'required',
                'tipo_envio_id' => 'required',
                'tipo_producto_id' => 'required',
                'estado_envio_id' => 'required',
            ];
        }elseif($nombre == 'Almacenaje'){
            $datos['conditions'] = [
                'codigo_envio' => 'required|exists:envios',
                'columna' => 'required',
                'fila' => 'required',
                'bandeja' => 'required',
                'nro_paquete' => 'required',
            ];
        }elseif($nombre == 'Entrega'){
            $datos['conditions'] = [
                'monto_a_pagar' => 'required',
                'estado_entrega_id' => 'required'
            ];
        }else{

        }

        return $datos;
    }

    public function guardarDatos(Request $request, $nombre, $empleado_id){
        $datos = [];
        $datos['error'] = 0;
        $registro_id;
        
        if($nombre == 'Clasificacion'){
            $registro = new Registro;
            $registro->save();
            
            $registro_id = $registro->id;
            
            $destinatario = new Destinatario;
            $destinatario->registro_id = $registro_id;
            $destinatario->apellido_paterno = $request->input('apellido_paterno');
            $destinatario->apellido_materno = $request->input('apellido_materno');
            $destinatario->nombre = $request->input('nombre');
            $destinatario->telefono = $request->input('telefono');
            $destinatario->calle_avenida = $request->input('calle_avenida');
            $destinatario->edificio_nro = $request->input('edificio_nro');
            $destinatario->pais_id = $request->input('pais_id_dest');
            $destinatario->ciudad_id = $request->input('ciudad_id');
            $destinatario->zona_id = $request->input('zona_id');                
            
            $envio = new Envio;
            $envio->registro_id = $registro_id;
            $envio->codigo_envio = $request->input('codigo_envio');
            $envio->fecha_hora_envio = $request->input('fecha_hora_envio');
            $envio->peso = $request->input('peso');
            $envio->pais_id = $request->input('pais_id_env');
            $envio->tipo_envio_id = $request->input('tipo_envio_id');
            $envio->tipo_producto_id = $request->input('tipo_producto_id');
            $envio->estado_envio_id = $request->input('estado_envio_id');
                
            $bitacora = new BitacoraDeRegistro;
            $bitacora->empleado_id = $empleado_id;
            $bitacora->registro_id = $registro_id;
            $bitacora->estado_bitacora_id = '1';
            
            
            $datos['registro'] = $registro;
            $destinatario->save();
            $datos['destinatario'] = $destinatario;
            $envio->save();
            $datos['envio'] = $envio;
            $bitacora->save();
            $datos['bitacora'] = $bitacora;
            
        }elseif($nombre == 'Almacenaje'){
            $codigo_envio = $request->input('codigo_envio');
            $envio = Envio::all()->where('codigo_envio', $codigo_envio)->first();
            $registro_id = $envio->registro_id;

            $almacenaje = Almacenaje::all()->where('registro_id', $registro_id)->first();
            
            if($almacenaje == null){
                $almacenaje = new Almacenaje;
                $almacenaje->registro_id = $registro_id;
                $almacenaje->columna = $request->input('columna');
                $almacenaje->fila = $request->input('fila');
                $almacenaje->bandeja = $request->input('bandeja');
                $almacenaje->nro_paquete = $request->input('nro_paquete');
                $almacenaje->ubicacion = $almacenaje->columna.$almacenaje->fila.$almacenaje->bandeja.'.'.$almacenaje->nro_paquete;
    
                $bitacora = new BitacoraDeRegistro;
                $bitacora->empleado_id = $empleado_id;
                $bitacora->registro_id = $registro_id;
                $bitacora->estado_bitacora_id = '1';
    
                $bitacora->save();
                $datos['bitacora'] = $bitacora;
                $almacenaje->save();
                $datos['almacenaje'] = $almacenaje;
            }else{
                $datos['error'] = 1;
            }

        }elseif($nombre == 'Entrega'){
            $codigo_envio = $request->input('codigo_envio');
            $envio = Envio::all()->where('codigo_envio', $codigo_envio)->first();
            $registro_id = $envio->registro_id;

            $entrega = new Entrega;
            $entrega->registro_id = $registro_id;
            $entrega->fecha_hora_entrega = '2010-10-10 10:10:10'; //SACAR!
            $entrega->monto_a_pagar = $request->input('monto_a_pagar');
            $entrega->estado_entrega_id = $request->input('estado_entrega_id'); 
    
            $factura = new Factura;
            $factura->registro_id = $registro_id;
            if($request->input('esFactura') == null){
                $factura->esFactura = '0';
                $factura->nit_factura = '1';
                $factura->nombre_factura = 'nombre_factura';
                $factura->monto_factura = 'monto_factura';
            }else{                    
                $factura->esFactura = '1';
                $factura->nit_factura = $request->input('nit_factura');
                $factura->nombre_factura = $request->input('nombre_factura');
                $factura->monto_factura = $request->input('monto_a_pagar');
            }
            
            $bitacora = new BitacoraDeRegistro;
            $bitacora->empleado_id = $empleado_id;
            $bitacora->registro_id = $registro_id;
            $bitacora->estado_bitacora_id = '1';
                
                
            $bitacora->save();
            $datos['bitacora'] = $bitacora;
            $entrega->save();
            $datos['entrega'] = $entrega;
            $factura->save();
            $datos['factura'] = $factura;
            
        }

        return $datos;
    }

    public function getPaises(){
        $paises = Pais::where('esActivo', '1')->groupBy('nombre')->get();
        return $paises;
    }

    public function getIata(){
        $paises = Pais::where('esActivo', '1')->get();
        return $paises;
    }

    public function getCiudades(){
        $ciudades = Ciudad::where('esActivo', '1')->get();
        return $ciudades;
    }

    public function getZonas(){
        $zonas = Zona::where('esActivo', '1')->get();
        return $zonas;
    }

    public function getTiposEnvio(){
        $tipos_envio = TipoEnvio::where('esActivo', 1)->get();
        return $tipos_envio;
    }

    public function getTiposProducto(){
        $tipos_producto = TipoProducto::where('esActivo', 1)->get();
        return $tipos_producto;
    }

    public function getEstadosEnvio(){
        $estados_envio = EstadoEnvio::where('esActivo', 1)->get();
        return $estados_envio;
    }

    public function getEstadosEntrega(){
        $estados_entrega = EstadoEntrega::where('esActivo', 1)->get();
        return $estados_entrega;
    }


    public function fillDataBase(){
        $bitacora = new Area;
        $bitacora->nombre = 'Almacenaje';
        $bitacora->esActivo = '1';
        $bitacora->save();
    }
}
