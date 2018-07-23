@extends('adminlte::page')

@section('htmlheader_title')
	Reporte
@endsection

@section('sidebar_reporte')
	class="active"
@endsection

@section('contentheader_title')
    
@endsection

@section('contentheader_level')
    Home
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
                
                <!-- Default box -->
				<div class="box">
                        {!! Form::open(['action' => 'ReporteController@reporte' , 'method' => 'GET']) !!}
                        <div class="box-header with-border">
                            <h3 class="box-title">Registro Diario</h3>
    
                            <div class="box-tools pull-right">
                                {{form::submit('Reporte', ['class' => 'btn btn-primary'])}}
                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            @if (count($datos['registros']) > 0)
                                <table class="table table-hover">
                                    <tr>
                                        <th>Codigo Envio</th>
                                        <th>Hora</th>
                                    </tr>
                                    @foreach ($datos['registros'] as $registro)
                                        <tr>
                                        <th>{{$registro->envio_codigo}}</th>
                                        <th>{{$registro->bitacora_hora}}</th>
                                        </tr>
                                    @endforeach    
                                </table>
                                @else
                                <div class="col-md-8 col-md-offset-2">
                                    NO REALIZO NINGUN REGISTRO EL DIA DE HOY
                                </div>
                                @endif
                        </div>
                        <!-- /.box-body -->
                        {!! Form::close() !!}
                    </div>
                    <!-- /.box -->

			</div>
		</div>
	</div>
@endsection
