@if ($datos['rango'] == 'Empleado')
@foreach ($datos['registros'] as $registro)
    Codigo: {{$registro->envio_codigo}}<br>
    Hora Registro: {{$registro->bitacora_hora}}<br>
    Estado: {{$registro->bitacora_estado_nombre}}<br><br>
    <b>Datos de Entrega</b><br>
    Monto a pagar: {{$registro->entrega_monto_a_pagar}} Bs<br>
    Estado Entrega: {{$registro->entrega_estado_nombre}}<br><br>
    @if ($registro->factura_es_factura == 1)
    <b>Datos de la Factura</b><br>
    Nro Factura: {{$registro->factura_nro}}<br>
    @if ($registro->factura_es_manual == 1)
        Nit: {{$registro->factura_nit}}<br>
        Nombre: {{$registro->factura_nombre}}<br>
        Monto: {{$registro->factura_monto}} Bs<br><br>    
    @endif  
    @endif
    <hr>
@endforeach
@endif