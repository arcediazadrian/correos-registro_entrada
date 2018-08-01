{{ csrf_field() }}
<h1>Datos del Destinatario</h1> 
<div class="row">
	<div class="col-md-6">                     
		<div class="form-group">
			{{form::label('apellido_paterno', 'Apellido Paterno')}}
			{{form::text('apellido_paterno', '', ['class' => 'form-control', 'placeholder' => 'Apellido Paterno', 'pattern'=>'[A-Za-z]+([\s][A-Za-z]+)*', 'title'=>'Apellido Paterno solo puede tener letras y espacios'])}}
		</div>
		<div class="form-group">
			{{form::label('apellido_materno', 'Apellido Materno')}}
			{{form::text('apellido_materno', '', ['class' => 'form-control', 'placeholder' => 'Apellido Materno', 'pattern'=>'[A-Za-z]+([\s][A-Za-z]+)*', 'title'=>'Apellido Materno solo puede tener letras y espacios'])}}
		</div>
		<div class="form-group">
			{{form::label('nombre', 'Nombre(s)')}}
			{{form::text('nombre', '', ['class' => 'form-control', 'placeholder' => 'Nombre(s)', 'pattern'=>'[A-Za-z]+([\s][A-Za-z]+)*', 'title'=>'Nombre solo puede tener letras y espacios'])}}
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="form-group">
			{{form::label('telefono', 'Telefono')}}
			{{form::text('telefono', '', ['class' => 'form-control', 'placeholder' => 'Telefono', 'pattern'=>'[0-9]+', 'title'=>'Telefono solo puede tener numeros'])}}   
		</div>
		<div class="form-group">
			{{form::label('calle_avenida', 'Calle o Avenida')}}
			{{form::text('calle_avenida', '', ['class' => 'form-control', 'placeholder' => 'Calle o Avenida'])}}
		</div>
		<div class="form-group">
			{{form::label('edificio_nro', 'Edificio o Nro')}}
			{{form::text('edificio_nro', '', ['class' => 'form-control', 'placeholder' => 'Edificio o Nro'])}}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3"> 
		<div class="form-group">
			<label for="pais_id_dest">Pais</label>
			<select class="form-control" style="width: 100%;" name="pais_id_dest" id="pais_id_dest">
				<option value="">Seleccione un pais</option>
				<!-- Se ponen los datos que sacamos de datos en el combobox -->
				@foreach ($datos['paises'] as $pais)
					<option value="{{$pais->id}}">{{$pais->nombre}}</option>
				@endforeach     
			</select>
		</div>
		<div class="form-group">
			<label>Ciudad</label>
			<select class="form-control" style="width: 100%;" name="ciudad_id" id="ciudad_id"> 
				<option value="">Seleccione una ciudad</option>
			</select>
		</div>
		<div class="form-group">
			<label>Zona</label>
			<select class="form-control" style="width: 100%;" name="zona_id" id="zona_id">
				<option value="">Seleccione una zona</option>
			</select>
		</div>
	</div>
</div>


<h1>Datos del Origen</h1>
<div class="row">
	<div class="col-md-6"> 
		<div class="form-group">
			{{form::label('codigo_envio', 'Codigo de Envio')}}
			{{form::text('codigo_envio', '', ['class' => 'form-control', 'placeholder' => 'Codigo de Envio', 'pattern'=>'[A-Z]{2}[0-9]{9}[A-Z]{2}', 'title'=>'Codigo de Envio debe tener 13 caracteres'])}}
		</div>
		<div class="form-group">
			<label>Pais de Envio</label>
			<select class="form-control select2" style="width: 100%;" name="pais_id_env" id="pais_id_env">
				<option value="">Seleccione un pais</option>
				<!-- Se ponen los datos que sacamos de datos en el combobox -->
				@foreach ($datos['iatas'] as $iata)
					<option value="{{$iata->id}}">({{$iata->iata}}) {{$iata->nombre}} - {{$iata->oficina_iata}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label>Fecha de Envio del Origen</label>
			<div class="input-group">
				<div class="input-group-prepend">		
					<span class="input-group-text"><i class="fa fa-calendar"></i></span>
					<!-- Se ponen maximos y minimos dependiendo de lo q nos pasan en datos -->
				<input type="date" id="fecha_hora_envio" name="fecha_hora_envio" min="2000-01-01" max="{{$datos['fecha']}}">
				</div>
			</div>
			<!-- /.input group -->
		</div>
	</div>
		
	<div class="col-md-6"> 
		<div class="form-group">
			{{form::label('peso', 'Peso del Envio')}}
			<div class="input-group">
				{{form::text('peso', '', ['class' => 'form-control', 'placeholder' => 'Peso del Envio', 'pattern'=>'[0-9]+([\.][0-9]+)?', 'title'=>'Peso solo puede tener numeros y un punto decimal si es necesario'])}}
				<span class="input-group-addon">Kg</span>
			</div>
		</div>
		<div class="form-group">
			<label>Tipo Servicio</label>
			<select class="form-control select2" style="width: 100%;" name="tipo_servicio_id" id="tipo_servicio_id">
				<option value="">Seleccione un tipo de servicio</option>
				<!-- Se ponen los datos que sacamos de datos en el combobox -->
				@foreach ($datos['tipos_servicio'] as $tipo_servicio)
					<option value="{{$tipo_servicio->id}}">{{$tipo_servicio->tipo_servicio}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label>Tipo Producto</label>
			<select class="form-control select2" style="width: 100%;" name="tipo_producto_id" id="tipo_producto_id">
				<option value="">Seleccione un tipo de producto</option>
				<!-- Se ponen los datos que sacamos de datos en el combobox -->
				@foreach ($datos['tipos_producto'] as $tipo_producto)
					<option value="{{$tipo_producto->id}}">{{$tipo_producto->tipo_producto}}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>				
<div class="row">
	<div class="col-md-6 col-md-offset-3"> 
		<div class="form-group">
			<label>Estado del Envio</label>
			<select class="form-control select2" style="width: 100%;" name="estado_envio_id" id="estado_envio_id">
				<option value="">Seleccione un estado de envio</option>
				<!-- Se ponen los datos que sacamos de datos en el combobox -->
				@foreach ($datos['estados_envio'] as $estado_envio)
					<option value="{{$estado_envio->id}}">{{$estado_envio->estado_envio}}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>

@section('script_clasificacion')
<!-- Se utilizan los scripts que se encuentran en public\js para que sirvan los combobox anidados de ciudad y zona -->
<script src="{{ asset('js/ciudades.js') }}"></script>
<script src="{{ asset('js/zonas.js') }}"></script>
@endsection