@extends('adminlte::page')

@section('htmlheader_title')
	Inicio
@endsection

@section('sidebar_home')
	class="active"
@endsection

@section('contentheader_title')
	
@endsection

@section('contentheader_level')
    Inicio
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">

				<!-- Default box -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Inicio</h3>
                    </div>
                    
					<div class="box-body">
						Bienvenid@ {{Auth::user()->name}}
					</div>
                    <!-- /.box-body -->
				</div>
                <!-- /.box -->
                
			</div>
		</div>
	</div>
@endsection
