@extends('adminlte::page')

@section('htmlheader_title')
	Registro
@endsection

@section('sidebar_registro')
	class="active"
@endsection

@section('contentheader_title')
	Registro
@endsection

@section('contentheader_description')
	Este es el registro de {{$datos['area']->nombre}}
@endsection

@section('contentheader_level')
    {{$datos['area']->nombre}} > Registro > Registrar
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">

                <div class="box box-primary">
                    {!! Form::open(['action' => 'RegistroController@guardar' , 'method' => 'POST']) !!}
                        <div class="box-header with-border">
                            <h3 class="box-title">Registro</h3>
                        </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                        <div class="box-body">
                            @if ($datos['area']->nombre == 'Clasificacion' && $datos['rango']->nombre == 'Empleado')
                            
                                @include('registros.formularios.clasificacion.registro')
								
                            @elseif($datos['area']->nombre == 'Almacenaje' && $datos['rango']->nombre == 'Empleado')

                                @include('registros.formularios.almacenaje')
                           
                            @elseif($datos['area']->nombre == 'Entrega' && $datos['rango']->nombre == 'Empleado')

                                @include('registros.formularios.entrega')

                            @endif
								
                        </div>
                        <!-- /.box-body -->
                
                        <div class="box-footer">
                            {{form::submit('Registrar', ['class' => 'btn btn-primary'])}}
                        </div>
                        <!-- /.box-footer -->
                    {!! Form::close() !!}
                </div>
                <!-- /.box -->

			</div>
		</div>
	</div>
@endsection
