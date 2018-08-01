@if ($datos['rango'] == 'Empleado')

<tr>
    <td colspan="3" align="center" id="tdb"><b>Registro</b></td>
    <td colspan="1" align="center" id="tdb"><b>Almacenaje</b></td>
</tr>
<tr>
    <td id="tdb"><b>Codigo</b></td>
    <td id="tdb"><b>Hora</b></td>
    <td id="tdb"><b>Estado</b></td>
    <td id="tdb"><b>Ubicacion</b></td>
</tr>

    @foreach ($datos['registros'] as $registro)
        <td id="tdb">{{$registro->envio_codigo}}</td>
        <td id="tdb">{{$registro->bitacora_hora}}</td>
        <td id="tdb">{{$registro->bitacora_estado_nombre}}</td>
        <td id="tdb">{{$registro->almacenaje_ubicacion}}</td>
    @endforeach
@endif

