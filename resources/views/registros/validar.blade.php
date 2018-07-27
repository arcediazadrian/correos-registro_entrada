@extends('adminlte::page')

@section('htmlheader_title')
	Registro
@endsection

@section('sidebar_registro')
	class="active"
@endsection

@section('contentheader_title')
	Validacion
@endsection


@section('contentheader_level')
    {{$datos['area']->nombre}} > Registro > Validar
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
                
                <!-- Default box -->
				<div class="box">
                    {!! Form::open(['action' => 'RegistroController@validar' , 'method' => 'POST']) !!}
                    <div class="box-header with-border">
                        <h3 class="box-title">Registros esperando validacion</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        
                        @if($datos['area']->nombre == 'Clasificacion' && $datos['rango']->nombre == 'Supervisor')

                            @include('registros.formularios.clasificacion.validacion')

                        @endif    
                    
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        {{form::submit('Validar', ['class' => 'btn btn-primary'])}}
                    </div>
                    <!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
                <!-- /.box -->

			</div>
		</div>
	</div>
@endsection
