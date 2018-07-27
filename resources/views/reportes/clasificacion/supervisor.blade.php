@foreach ($datos['registros'] as $registro)
Codigo: {{$registro->envio_codigo}}<br>
Hora Registro: {{$registro->bitacora_hora}}<br>
Estado: {{$registro->bitacora_estado_nombre}}<br><br>
<hr>
@endforeach