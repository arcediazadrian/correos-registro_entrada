@if ($datos['rango'] == 'Empleado')

<tr>
        <td colspan="3" align="center" id="tdb"><b>Registro</b></td>
        <td colspan="2" align="center" id="tdb"><b>Entrega</b></td>
        <td colspan="4" align="center" id="tdb"><b>Factura</b></td>
    </tr>
    <tr>
        <td id="tdb"><b>Codigo</b></td>
        <td id="tdb"><b>Hora</b></td>
        <td id="tdb"><b>Estado</b></td>
        <td id="tdb"><b>Monto</b></td>
        <td id="tdb"><b>Estado</b></td>
        <td id="tdb"><b>Nro</b></td>
        <td id="tdb"><b>Nit</b></td>
        <td id="tdb"><b>Nombre</b></td>
        <td id="tdb"><b>Monto</b></td>
    </tr>
    
@foreach ($datos['registros'] as $registro)
    <td id="tdb">{{$registro->envio_codigo}}</td>
    <td id="tdb">{{$registro->bitacora_hora}}</td>
    <td id="tdb">{{$registro->bitacora_estado_nombre}}</td>
    <td id="tdb">{{$registro->entrega_monto_a_pagar}}</td>
    <td id="tdb">{{$registro->entrega_estado_nombre}}</td>
    <td id="tdb">{{$registro->factura_nro}}</td>
    <td id="tdb">{{$registro->factura_nit}}</td>
    <td id="tdb">{{$registro->factura_nombre}}</td>
    <td id="tdb">{{$registro->factura_monto}}</td> 
@endforeach
@endif