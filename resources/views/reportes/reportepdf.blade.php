
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte {{$datos['empleado']['nombre']}} {{$datos['fecha']['fecha']}}</title>
    <style type="text/css">
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
        }
        table.tableizer-table {
            font-size: 12px;
            border: 1px solid #000000;
        }
        .tableizer-table td {
            padding: 4px;
            margin: 3px;
            border: 1px solid #000000;
        }
        .tableizer-table th {
            background-color: #104E8B;
            color: #FFF;
        }
        .tableBorder{
            border: 1px solid #000000;
            border-collapse: collapse;
        }
        .tableEspaciosTitulo{
            margin-top:-2em;
            border:1px solid black;
            border-radius:5px;
            -webkit-border-radius:5px;
            -moz-border-radius:5px;
            padding: 10px;
        }
        .tableEspacioTituloPrincipal{
            margin-top: -1.5em;
            padding: 10px;
        }
        .tableEspacios{
            margin-bottom:2em;
            margin-top:-2em;
        }
        .horas{
            margin-left:13em;
            width:60%;
            text-align:center;
            font-weight:bold;
            font-size:12px;
            color:red !important;
            padding:5px;
            border:1px red solid !important;
            border-radius:4px;
        }
        .nota,.impresion{
            font-style:italic;
            font-size:9.5px;
        }
        .nombres{
            font-size: 11px;
            color: green;
            font-weight: bold;
        }
        .cites, .cites2{
            font-size: 9px;
            color: red;
            font-weight: bold;
            text-align: center;
            padding:3px;
            border: 1px red solid;
            border-radius:3px;
        }
        .cites2{
            margin-top: -1em;
        }
        .cite{
            font-size: 10px;
            color: red;
            font-weight: bold;
            text-align: right;
            margin-top: -1em;
        }
    </style>
</head>
<body>
<!--ORIGINAL Y COPIA-->

<table width="100%">
    <tr>
        <td align="left" width="25%"><img src="{{ public_path() . '/img/reportes/escudo_bolivia.gif' }}" width="95px" height="80px"/></td>
        <td align="center"><b>Estado Plurinacional de Bolivia</b><br><div style="font-size:9px;">Agencia Boliviana de Correos<br><hr/></div></td>
        <td align="right" width="25%"><img src="{{  public_path() . '/img/reportes/Logo.jpg' }}" width="100px" height="50px"/></td>
    </tr>
</table>
<div class="tableEspacioTituloPrincipal">
    <table width="100%">
        <tr>
            <td width="4%"></td>
            <td width="4%"></td>
            <td width="4%"></td>
            <td align="center" rowspan="3" width="65%"><h2>REPORTE {{$datos['fecha']['fecha']}}</h2></td>
        <td width="5%"><div class="cites">DIA</div></td>
            <td width="5%"><div class="cites">MES</div></td>
            <td width="5%"><div class="cites">AÑO</div></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        <td><div class="cites2">{{$datos['fecha']['dia']}}</div></td>
            <td><div class="cites2">{{$datos['fecha']['mes']}}</div></td>
            <td><div class="cites2">{{$datos['fecha']['ano']}}</div></td>
        </tr>
        <tr>
            <td colspan="3"><div class="cites"></div></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
<br>

Emplead@: {{$datos['empleado']['nombre']}}<br>
Rango: {{$datos['rango']}}<br>
Ciudad: {{$datos['ciudad']}}<br>
Registros: {{count($datos['registros'])}}<br><br><br><br>

<div class="tableEspaciosTitulo">
        @if ($datos['area'] == 'Clasificacion' && $datos['rango'] == 'Empleado')
            @include('reportes.clasificacion.empleado')
        @elseif($datos['area'] == 'Clasificacion' && $datos['rango'] == 'Supervisor')
            @include('reportes.clasificacion.supervisor')
        @elseif($datos['area'] == 'Almacenaje' && $datos['rango'] == 'Empleado')
            @include('reportes.almacenaje')
        @elseif($datos['area'] == 'Entrega' && $datos['rango'] == 'Empleado')
            @include('reportes.entrega')
        @endif 
</div>

</body>
</html>
