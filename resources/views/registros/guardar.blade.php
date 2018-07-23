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
    {{$datos['area']->nombre}} > Registro > Datos Guardados
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-9 col-md-offset-1">

                <div class="box box-primary">
                    <form role="form" action='{{url('registro/registro')}}' method='GET'>
                        <div class="box-header with-border">
                        <h3 class="box-title">Registro</h3>
                        </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                        <div class="box-body">
                            Datos Almacenados
                        </div>
                        <!-- /.box-body -->
                
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Continuar</button>
                        </div>
                        <!-- /.box-footer -->

                    </form>
                </div>
                <!-- /.box -->

			</div>
		</div>
	</div>
@endsection
