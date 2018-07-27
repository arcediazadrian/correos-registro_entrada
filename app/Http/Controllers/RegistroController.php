<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use Carbon\Carbon;

use App\Registro;
use App\Empleado;
use App\User;
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
use App\Rango;
use App\TipoServicio;
use App\TarifarioEntrega;
use App\EstadoBitacora;

class RegistroController extends Controller
{
    /*
    * registro: En esta funcion se redirecciona a la pagina correcta dependiendo del area y rango(cargo)
    */
    public function registro(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Entrega' && $rango->nombre == 'Empleado'){
            return redirect('registro/buscar');
        }elseif(($area->nombre == 'Clasificacion' || $area->nombre == 'Almacenaje') && $rango->nombre == 'Empleado'){
            return redirect('registro/registroPaquete');
        }elseif($area->nombre == 'Clasificacion' && $rango->nombre == 'Supervisor'){
            return redirect('registro/validacionPaquete');
        }else{
            return "AREA NOT FOUND";
        }
    }

    /*
    * registroEntrada: recibe el codigo de envio de un paquete, si existe muestra un formulario sino envia un error
    */
    public function registroEntrega(Request $request){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Clasificacion' || $area->nombre == 'Almacenaje' || $rango->nombre != 'Empleado'){
            return redirect('registro/registroPaquete')->with('error', 'Pagina no autorizada');
        }

        //codigo de envio
        $envio = Envio::all()->where('codigo_envio',$request->input('codigo_envio'))->first();
        
        if($envio == null){
            //no se ha registrado un paquete con el codigo de envio entonces devuelve un error
            return redirect('registro/buscar')->with('error', 'Paquete no encontrado');
        }else{
            $entrega = Entrega::all()->where('registro_id', $envio->registro_id)->first();
            $factura = Factura::all()->where('registro_id', $envio->registro_id)->first();
            if($entrega == null && $factura == null){
                //no se ha entregado este paquete todavia
                $registro_id = $envio->registro_id;
                $almacenaje = Almacenaje::where('registro_id', $registro_id)->first();
                if($almacenaje == null){
                    //no se ha registrado en almacenaje todavia
                    return redirect('registro/buscar')->with('error', 'No se ha creado un registro de Almacenamiento con este codigo de envio');
                }else{
                    //si se ha registrado en almacenaje
                    $ubicacion = 'Ubicacion en el almacen: '.$almacenaje->ubicacion;

                    $tipo_servicio_id = $envio->tipo_servicio_id;
                    $peso = $envio->peso;
                    $tarifa = $this->generarTarifa($tipo_servicio_id, $peso);
                    
                    $datos = ['empleado'=>$empleado, 'area'=>$area, 'codigo_envio'=>$envio->codigo_envio, 'ubicacion'=>$ubicacion, 'rango'=>$rango, 'tarifa'=>$tarifa];
                    $datos = $this->generarDatos($datos, $datos['area']->nombre, $rango);
                    
                    return view('registros.registrar', array('datos'=>$datos));   
                }
            }else{
                //ya se entrego este paquete entonces manda un error
                return redirect('registro/buscar')->with('error', 'Ya se creo un registro de Entrega con esta codigo de envio');
            }
        }
        
    }

    /*
    * registroPaquete: Le pasa los datos necesarios al formulario de registro de clasificacion y almacenaje y lo muestra 
    */
    public function registroPaquete(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Entrega' || $rango->nombre != 'Empleado'){
            return redirect('registro/buscar')->with('error', 'Pagina no autorizada');
        }
        
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'rango'=>$rango];
        
        //genera los datos necesario para el formulario dependiedo del area y rango
        $datos = $this->generarDatos($datos, $datos['area']->nombre, $rango);

        return view('registros.registrar', array('datos'=>$datos));   
    }

    /*
    * validacionPaquete: muestra los registros que pueden ser validados
    */
    public function validacionPaquete(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Clasificacion' && $rango->nombre == 'Supervisor'){

        $registros = $this->generarRegistros($empleado->ciudad_id, $area->nombre, $rango->nombre);
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'rango'=>$rango, 'registros'=>$registros];
        
        return view('registros.validar', array('datos'=>$datos));  
        }
    }

    /*
    * validar: valida los registros escogidos en la bitacora y crea un nuevo registro de bitacora donde 
    * dice a que area se deriva el paquete
    */
    public function validar(Request $request){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id',$id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Clasificacion' && $rango->nombre == 'Supervisor'){
        $registros = $this->generarRegistros($empleado->ciudad_id, $area->nombre, $rango->nombre);

        $datos = ['area'=>$area];

        foreach ($registros as $registro) {
            if($request->input($registro->envio_codigo) != null){
                //ver si se ha escogido el paquete con codigo de envio para ser validado
                $bitacora = null;
                if($registro->bitacora_estado_id == 2){
                    $bitacora = BitacoraDeRegistro::all()->where('estado_bitacora_id', 2)->where('registro_id', $registro->registro_id)->first(); //Registrado
                }elseif($registro->bitacora_estado_id == 11){
                    $bitacora = BitacoraDeRegistro::all()->where('estado_bitacora_id', 11)->where('registro_id', $registro->registro_id)->first(); //Registrado
                }
                //cambiar el registro de la bitacora de registrado a validado 
                $bitacora->estado_bitacora_id = '12'; // Validado
                $bitacora->save();
                $datos['bitacora_empleado'] = $bitacora;

                //Crear un nuevo registro con el area a donde se va a derivar el paquete
                $bitacora = new BitacoraDeRegistro;
                $bitacora->empleado_id = $empleado->id;
                $bitacora->registro_id = $registro->registro_id;
                $bitacora->ciudad_id = $empleado->ciudad_id;
                if($registro->bitacora_estado_id == 2){
                    $bitacora->estado_bitacora_id = '6'; // Derivacion a Almacen
                }elseif($registro->bitacora_estado_id == 11){
                    $bitacora->estado_bitacora_id = '11'; // Derivacion a Almacen
                }
                $bitacora->save();
                $datos['bitacora_supervisor'] = $bitacora;

            }
        }

        return view('registros.guardar', array('datos'=>$datos));
        }

    }
    
    /*
    * guardar: guarda los datos enviados del formulario de registro
    */
    public function guardar(Request $request){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        $validacion = $this->generarDatosValidacion($area->nombre, $rango);

        $this->validate($request, $validacion['conditions'], $validacion['errormsgs']);
                
        $datos = $this->guardarDatos($request, $area->nombre, $empleado, $rango);
        // Si existen errores al guardar los datos mandar un error
        if($datos['error'] == 1){
            return redirect('registro/registroPaquete')->with('error', 'Ya se creo un registro de '.$area->nombre.' con este codigo de envio');
        }else{
            $datos['empleado'] = $empleado;
            $datos['area'] = $area;
    
            return view('registros.guardar', array('datos'=>$datos));
        }
    }

    /*
    * buscar: muestra el formulario de busqueda de paquetes para entregas
    */
    public function buscar(){
        $id = auth()->user()->id;
        $empleado = Empleado::all()->where('user_id', $id)->first();
        $area = Area::find($empleado->area_id);
        $rango = Rango::find($empleado->rango_id);

        if($area->nombre == 'Clasificacion' || $area->nombre == 'Almacenaje' || $rango->nombre != 'Empleado'){
            return redirect('registro/registroPaquete')->with('error', 'Pagina no autorizada');
        }
        $datos = ['empleado'=>$empleado, 'area'=>$area, 'rango'=>$rango];

        return view('registros.buscar',array('datos'=>$datos));

    }

    /*
    * generarDatos: genera los datos necesarios para llenar los campos parametricos en los formularios
    */
    public function generarDatos($datos, $nombre, $rango){
        if($nombre == 'Clasificacion' && $rango->nombre == 'Empleado'){
            $datos['paises'] = $paises = $this->getPaises();
            $datos['iatas'] = $this->getIata();
            $datos['ciudades'] = $this->getCiudades();
            $datos['zonas'] = $this->getZonas();
            $datos['tipos_servicio'] = $this->getTiposServicio();
            $datos['tipos_producto'] = $this->getTiposProducto();
            $datos['estados_envio'] = $this->getEstadosEnvio();
            $datos['fecha'] = Carbon::now()->toDateString();
        }elseif($nombre == 'Entrega' && $rango->nombre == 'Empleado'){
            $datos['estados_entrega'] = $this->getEstadosEntrega();
        }else{
            
        }

        return $datos;
    }

    /*
    * generarDatosValidacion: generar condiciones de validacion de los formularios
    */
    public function generarDatosValidacion($nombre, $rango){
        $datos['errormsgs'] = ['required'=>'El campo :attribute es obligatorio', 
                               'unique'=>':attribute debe ser unico',
                               'exists'=>':attribute no existe',
                            ];

        if($nombre == 'Clasificacion' && $rango->nombre == 'Empleado'){
            $datos['conditions'] = [
                'pais_id_dest' => 'required',
                'ciudad_id' => 'required',
                'codigo_envio' => 'required|unique:envios',
                'peso' => 'required',
                'tipo_servicio_id' => 'required',
                'tipo_producto_id' => 'required',
                'estado_envio_id' => 'required',
            ];
        }elseif($nombre == 'Almacenaje' && $rango->nombre == 'Empleado'){
            $datos['conditions'] = [
                'codigo_envio' => 'required|exists:envios',
                'columna' => 'required',
                'fila' => 'required',
                'bandeja' => 'required',
                'nro_paquete' => 'required',
            ];
        }elseif($nombre == 'Entrega' && $rango->nombre == 'Empleado'){
            $datos['conditions'] = [
                'estado_entrega_id' => 'required'
            ];
        }else{

        }

        return $datos;
    }

    /*
    * guardarDatos: guarda los datos en la base de datos y si existe algun error lo manda
    * en esta funcion tambien se crean las bitacoras y se hace la logica para sus estados
    */
    public function guardarDatos(Request $request, $nombre, $empleado, $rango){
        $datos = [];
        $datos['error'] = 0;
        $registro_id;
        
        if($nombre == 'Clasificacion' && $rango->nombre == 'Empleado'){
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
            $envio->tipo_servicio_id = $request->input('tipo_servicio_id');
            $envio->tipo_producto_id = $request->input('tipo_producto_id');
            $envio->estado_envio_id = $request->input('estado_envio_id');
                
            $bitacora = new BitacoraDeRegistro;
            $bitacora->empleado_id = $empleado->id;
            $bitacora->ciudad_id = $empleado->ciudad_id;
            $bitacora->registro_id = $registro_id;
            if($request->input('estado_envio_id') == 5){
                // No observado
                $bitacora->estado_bitacora_id = '2'; //Registrado
            }elseif($request->input('estado_envio_id') == 4){
                //Observado
                $bitacora->estado_bitacora_id = '11'; //Der. a boletin
            }
            
            
            $datos['registro'] = $registro;
            $destinatario->save();
            $datos['destinatario'] = $destinatario;
            $envio->save();
            $datos['envio'] = $envio;
            $bitacora->save();
            $datos['bitacora'] = $bitacora;
            
        }elseif($nombre == 'Almacenaje' && $rango->nombre == 'Empleado'){
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
                $bitacora->empleado_id = $empleado->id;
                $bitacora->registro_id = $registro_id;
                $bitacora->ciudad_id = $empleado->ciudad_id;
                $bitacora->estado_bitacora_id = '3'; //Admitido
    
                $bitacora->save();
                $datos['bitacora'] = $bitacora;
                $almacenaje->save();
                $datos['almacenaje'] = $almacenaje;
            }else{
                $datos['error'] = 1;
            }

        }elseif($nombre == 'Entrega' && $rango->nombre == 'Empleado'){
            $codigo_envio = $request->input('codigo_envio');
            $envio = Envio::all()->where('codigo_envio', $codigo_envio)->first();
            $registro_id = $envio->registro_id;

            $entrega = new Entrega;
            $entrega->registro_id = $registro_id;
            $entrega->monto_a_pagar = $request->input('tarifa');
            $entrega->estado_entrega_id = $request->input('estado_entrega_id'); 
    
            $factura = new Factura;
            $factura->registro_id = $registro_id;
            if($request->input('tarifa') == 0){
                $factura->esFactura = '0';
                $factura->esManual = '0';
            }else{
                $factura->esFactura = '1';
                $factura->nro_factura = $request->input('nro_factura');
                if($request->input('esManual') != null){
                    $factura->esManual = '1';
                    $factura->nit_factura = $request->input('nit_factura');
                    $factura->nombre_factura = $request->input('nombre_factura');
                    $factura->monto_factura = $request->input('tarifa');
                }else{
                    $factura->esManual = '0';
                }
                    
            }
            
            $estado_id = '0';
            if($request->input('estado_entrega_id') == 1){
                //Entregado
                $estado_id = '8';
            }elseif($request->input('estado_entrega_id') == 3){
                //Aduana
                $estado_id = '9';
            }elseif($request->input('estado_entrega_id') == 4){
                //Rechazado
                $estado_id = '10';
            }   
            $bitacora = new BitacoraDeRegistro;
            $bitacora->empleado_id = $empleado->id;
            $bitacora->ciudad_id = $empleado->ciudad_id;
            $bitacora->registro_id = $registro_id;
            $bitacora->estado_bitacora_id = $estado_id;
                
                
            $bitacora->save();
            $datos['bitacora'] = $bitacora;
            $entrega->save();
            $datos['entrega'] = $entrega;
            $factura->save();
            $datos['factura'] = $factura;
            
        }

        return $datos;
    }

    /*
    * getCiudadesDePais: toma un id de un pais y devuelve sus ciudades
    */
    public function getCiudadesDePais($id) 
    {
        $pais = Pais::find($id);
        $states = DB::table("ciudads")->where("pais_nombre",$pais->nombre)->pluck("nombre","id");

        return json_encode($states);

    }

    /*
    * getZonasDeCiudad: toma un id de una ciudad y devuleve sus zonas
    */
    public function getZonasDeCiudad($id) 
    {
        $states = DB::table("zonas")->where("ciudad_id",$id)->pluck("nombre","id");

        return json_encode($states);

    }

    /*
    * generarTarifa: genera la tarifa de un paquete dependiendo de su peso y el tipo de servicio
    */
    public function generarTarifa($tipo_servicio_id, $peso){
        $tipo_servicio = TipoServicio::find($tipo_servicio_id);
        $tarifa = 0;
        if($tipo_servicio->tipo_servicio == 'Encomienda' || $tipo_servicio->tipo_servicio == 'Pequeño Paquete'){
            $tarifa = TarifarioEntrega::all()->where('tipo_servicio_id', $tipo_servicio_id)
                                             ->where('limite_superior', '>=', $peso)
                                             ->where('limite_inferior', '<', $peso)->first();
            $tarifa = $tarifa->tarifa;
        }

        return $tarifa;

    }

    /*
    * generarRegistros: genera los registros a ser validados de una ciudad y area
    */
    public function generarRegistros($id_ciudad, $area, $rango){
        if($area == 'Clasificacion' && $rango == 'Supervisor'){
            $registros = DB::select('SELECT B.registro_id as registro_id, B.updated_at as bitacora_fecha, B.empleado_id as bitacora_empleado_id, B.estado_bitacora_id as bitacora_estado_id, B.ciudad_id as bitacora_ciudad_id,
                                            E.codigo_envio as envio_codigo, E.peso as envio_peso, E.tipo_servicio_id as envio_tipo_servicio_id, E.tipo_producto_id as envio_tipo_producto_id
                                     FROM envios E, bitacora_de_registros B, empleados EM
                                     WHERE B.registro_id = E.registro_id and (B.estado_bitacora_id = 2 or B.estado_bitacora_id = 11)  and B.empleado_id = EM.id and EM.rango_id = 1 and B.ciudad_id = '.$id_ciudad);// and DATE(B.created_at) = "'.$fecha.'"');
        
            foreach ($registros as $registro) {
                $empleado = Empleado::find($registro->bitacora_empleado_id);
                $user = User::find($empleado->user_id);
                $registro->empleado_nombre = $user->name;   

                //Estos ifs sirven para mostrar en la tabla el estado al q cambiara el estado de bitacora una vez validados los registros
                if($registro->bitacora_estado_id == 2){
                    $tipo_servicio = TipoServicio::find($registro->envio_tipo_servicio_id);
                    if($tipo_servicio->tipo_servicio == 'Encomienda'){
                        $registro->destino_interno = 'Almacen';
                    }elseif($tipo_servicio->tipo_servicio == 'Pequeño Paquete'){
                        $registro->destino_interno = 'Almacen';
                    }
                }elseif($registro->bitacora_estado_id == 11){
                    $estado_bitacora = EstadoBitacora::find($registro->bitacora_estado_id);
                    if($estado_bitacora->area_id != null){
                        $area = Area::find($estado_bitacora->area_id);
                        $registro->destino_interno = $area->nombre;
                    }
                }
            }

        }
        
        return $registros;
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

    public function getTiposServicio(){
        $tipos_servicio = TipoServicio::where('esActivo', 1)->get();
        return $tipos_servicio;
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
}
