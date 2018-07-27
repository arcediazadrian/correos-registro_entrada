<!-- Esta vista sirve para los usuarios de entrega, sirve para que puedan buscar si los paquetes estan en almacenaje -->

@extends('adminlte::layouts.app')

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
    {{$datos['area']->nombre}} > Registro > Buscar Paquete
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-9 col-md-offset-1">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar Paquete</h3>
                    </div>
                    <!-- /.box-header -->
                    
                    <!-- form start -->
                    {!! Form::open(['action' => 'RegistroController@registroEntrega' , 'method' => 'POST']) !!}
                        <div class="box-body">
                            {{form::label('codigo_envio', 'Codigo de Envio')}}
                            {{form::text('codigo_envio', '', ['class' => 'form-control', 'placeholder' => 'Codigo de Envio', 'pattern'=>'[A-Za-z0-9]{13}', 'title'=>'Codigo de Envio debe tener 13 caracteres'])}}
                            <!-- , 'pattern'=>'[A-Za-z0-9]{13}' -->
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
