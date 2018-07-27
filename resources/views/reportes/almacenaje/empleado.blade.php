@if ($datos['rango'] == 'Empleado')
@foreach ($datos['registros'] as $registro)
    Codigo: {{$registro->envio_codigo}}<br>
    Hora Registro: {{$registro->bitacora_hora}}<br>
    Estado: {{$registro->bitacora_estado_nombre}}<br><br>
    <b>Datos del Almacenaje</b><br>
    Ubicacion : {{$registro->almacenaje_ubicacion}}<br><br>
    <hr>
@endforeach
@endif