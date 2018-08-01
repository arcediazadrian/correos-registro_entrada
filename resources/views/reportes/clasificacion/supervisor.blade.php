<tr>
        <td colspan="3" align="center" id="tdb"><b>Registro</b></td>
    </tr>
    <tr>
        <td id="tdb"><b>Codigo</b></td>
        <td id="tdb"><b>Hora</b></td>
        <td id="tdb"><b>Estado</b></td>
    </tr>

@foreach ($datos['registros'] as $registro)
<td id="tdb">{{$registro->envio_codigo}}</td>
<td id="tdb">{{$registro->bitacora_hora}}</td>
<td id="tdb">{{$registro->bitacora_estado_nombre}}</td>
@endforeach