@if ($datos['rango'] == 'Empleado')

<tr>
    <td colspan="3" align="center" id="tdb"><b>Registro</b></td>
    <td colspan="3" align="center" id="tdb"><b>Destinatario</b></td>
    <td colspan="3" align="center" id="tdb"><b>Envio</b></td>
</tr>
<tr>
    <td id="tdb"><b>Codigo</b></td>
    <td id="tdb"><b>Hora</b></td>
    <td id="tdb"><b>Estado</b></td>
    <td id="tdb"><b>Apellido</b></td>
    <td id="tdb"><b>Nombre</b></td>
    <td id="tdb"><b>Pais</b></td>
    <td id="tdb"><b>Peso</b></td>
    <td id="tdb"><b>Tipo de Servicio</b></td>
    <td id="tdb"><b>Tipo de Producto</b></td>
</tr>
    
@foreach ($datos['registros'] as $registro)
    <tr>
        <td id="tdb">{{$registro->envio_codigo}}</td>
        <td id="tdb">{{$registro->bitacora_hora}}</td>
        <td id="tdb">{{$registro->bitacora_estado_nombre}}</td>
        <td id="tdb">{{$registro->destinatario_apellido_paterno}}</td>
        <td id="tdb">{{$registro->destinatario_nombre}}</td>
        <td id="tdb">{{$registro->destinatario_pais_nombre}}</td>
        <td id="tdb">{{$registro->envio_peso}}kg</td>
        <td id="tdb">{{$registro->envio_tipo_servicio_nombre}}</td>
        <td id="tdb">{{$registro->envio_tipo_producto_nombre}}</td>
    </tr>

@endforeach
@endif