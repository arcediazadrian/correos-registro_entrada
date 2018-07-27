@if ($datos['rango'] == 'Empleado')
    
@foreach ($datos['registros'] as $registro)
Codigo: {{$registro->envio_codigo}}<br>
Hora Registro: {{$registro->bitacora_hora}}<br>
Estado: {{$registro->bitacora_estado_nombre}}<br><br>
<b>Datos del Destinatario</b><br>
Apellido Paterno : {{$registro->destinatario_apellido_paterno}}<br>
Apellido Materno : {{$registro->destinatario_apellido_materno}}<br>
Nombre : {{$registro->destinatario_nombre}}<br>
Telefono: {{$registro->destinatario_telefono}}<br>
Calle o Avenida: {{$registro->destinatario_calle_avenida}}<br>
Edificio o Nro: {{$registro->destinatario_edificio_nro}}<br>
    Telefono: {{$registro->destinatario_telefono}}<br>
    Pais: {{$registro->destinatario_pais_nombre}}<br>
    Ciudad: {{$registro->destinatario_ciudad_nombre}}<br>
    Zona: {{$registro->destinatario_zona_nombre}}<br><br>
    <b>Datos del Envio</b><br>
    Fecha de Envio del Origen: {{$registro->envio_fecha}}<br>
    Peso del Envio: {{$registro->envio_peso}}<br>
    Pais de Origen: {{$registro->envio_pais_nombre}}<br>
    Tipo de Servicio: {{$registro->envio_tipo_servicio_nombre}}<br>
    Tipo de Producto: {{$registro->envio_tipo_producto_nombre}}<br>
    Estado del Envio: {{$registro->envio_estado_nombre}}<br><br>
    <hr>
    @endforeach
@endif