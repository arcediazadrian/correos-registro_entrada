@if (count($datos['registros']) > 0)
    <table class="table table-hover">
        <tr>
            <th>Codigo Envio</th>
            <th>Peso Envio</th>
            <th>Destino interno</th>
            <th>Empleado</th>
            <th>Fecha y Hora</th>
            <th>Validar</th>
        </tr>
        @foreach ($datos['registros'] as $registro)
            <tr>
                <th>{{$registro->envio_codigo}}</th>
                <th>{{$registro->envio_peso}}</th>
                <th>{{$registro->destino_interno}}</th>
                <th>{{$registro->empleado_nombre}}</th>
                <th>{{$registro->bitacora_fecha}}</th>
                <th>
                    <div class="form-group">
                        <input type="checkbox" class="flat-red" name="{{$registro->envio_codigo}}" id="{{$registro->envio_codigo}}">
                    </div>
                </th>
            </tr>
        @endforeach    
    </table>
@else
    <div class="col-md-8 col-md-offset-2">
        NO EXISTE NINGUN REGISTRO ESPERANDO SER VALIDADO
    </div>
@endif