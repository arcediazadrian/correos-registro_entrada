<div class="alert alert-success">
    {{$datos['ubicacion']}}
</div>

{{form::hidden('codigo_envio', $datos['codigo_envio'], [])}}
{{form::hidden('tarifa', $datos['tarifa'], [])}}
    
<h1 align="center">Datos de la Entrega</h1>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="form-group">
            <label>Estado de la Entrega</label>
            <select class="form-control select2" style="width: 100%;" name="estado_entrega_id" id="estado_entrega_id">
                <option value="">Seleccione un estado de entrega</option>
                @foreach ($datos['estados_entrega'] as $estado_entrega)
                    <option value="{{$estado_entrega->id}}">{{$estado_entrega->estado_entrega}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@if ($datos['tarifa'] > 0)
<h1 align="center">Datos de la Factura</h1>
<div class="row">
        <div class="col-md-6 col-md-offset-3">
        <div class="form-group">
            {{form::label('nro_factura', 'Nro Factura')}}
            {{form::text('nro_factura', '', ['class' => 'form-control', 'placeholder' => 'Nro Factura', 'required', 'pattern'=>'[0-9]+', 'title'=>'El numero de factura solo puede contener numeros'])}}
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" class="flat-red" name="esManual" id="esManual" onclick="enableDisable(this.checked, 'nit_factura', 'nombre_factura')">
                <script language="javascript">
                function enableDisable(bEnable, textBoxID1, textBoxID2)
                {
                    document.getElementById(textBoxID1).disabled = !bEnable;
                    document.getElementById(textBoxID1).required = bEnable;
                    document.getElementById(textBoxID2).disabled = !bEnable;
                    document.getElementById(textBoxID2).required = bEnable;
                }
                </script>
                Factura Manual
            </label>
        </div>
            
        <div class="form-group">
            {{form::label('nit_factura', 'NIT')}}
            {{form::text('nit_factura', '', ['class' => 'form-control', 'placeholder' => 'NIT', 'disabled', 'pattern'=>'[0-9]{5,}', 'title'=>'NIT debe tener al menos 5 digitos'])}}
        </div>
        <div class="form-group">
            {{form::label('nombre_factura', 'Nombre')}}
            {{form::text('nombre_factura', '', ['class' => 'form-control', 'placeholder' => 'Nombre', 'disabled', 'pattern'=>'[A-Za-z]+([\s][A-Za-z]+)*', 'title'=>'Nombre debe tener solo letras y espacios'])}}
        </div>
    </div>
</div>
@endif
    