<title>Reporte {{$datos['empleado']['nombre']}} {{$datos['fecha']}}</title>
Emplead@: {{$datos['empleado']['nombre']}}<br>
ID: {{$datos['empleado']['id']}}<br>
Fecha: {{$datos['fecha']}}<br>
Registro realizados en el dia: {{count($datos['registros'])}}<br><br><br>


<hr>
@if ($datos['area']->nombre == 'Clasificacion')
    @include('reportes.clasificacion')
@elseif($datos['area']->nombre == 'Almacenaje')
    @include('reportes.almacenaje')
@elseif($datos['area']->nombre == 'Entrega')
    @include('reportes.entrega')
@endif