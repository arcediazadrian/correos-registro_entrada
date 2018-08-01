<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="form-group">
            {{form::label('codigo_envio', 'Codigo de Envio')}}
            {{form::text('codigo_envio', '', ['class' => 'form-control', 'placeholder' => 'Codigo de Envio', 'pattern'=>'[A-Z]{2}[0-9]{9}[A-Z]{2}', 'title'=>'Codigo de Envio debe tener 13 caracteres'])}}
        </div>
    </div>
</div>
    
<h1>Datos de Almacenaje</h1> 
<div class="row">
    <div class="col-md-6"> 
        <div class="form-group">
            {{form::label('columna', 'Columna')}}
            {{form::text('columna', '', ['class' => 'form-control', 'placeholder' => 'Columna', 'pattern'=>'[0-9]+', 'title'=>'Columna solo puede tener numeros'])}}
        </div>
        <div class="form-group">
            {{form::label('fila', 'Fila')}}
            {{form::text('fila', '', ['class' => 'form-control', 'placeholder' => 'Fila', 'pattern'=>'[a-zA-Z]+', 'title'=>'Fila solo puede tener letras'])}}
        </div>
    </div>
    <div class="col-md-6"> 
        <div class="form-group">
            {{form::label('bandeja', 'Bandeja')}}
            {{form::text('bandeja', '', ['class' => 'form-control', 'placeholder' => 'Bandeja', 'pattern'=>'[a-zA-Z]+', 'title'=>'Bandeja solo puede tener letras'])}}
        </div>
        <div class="form-group">
            {{form::label('nro_paquete', 'Nro Paquete')}}
            {{form::text('nro_paquete', '', ['class' => 'form-control', 'placeholder' => 'Nro Paquete', 'pattern'=>'[0-9]+', 'title'=>'Paquete solo puede tener numeros'])}}
        </div>
    </div>
</div>