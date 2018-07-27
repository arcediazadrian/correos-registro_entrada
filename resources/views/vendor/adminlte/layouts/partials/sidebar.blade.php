<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Gravatar::get($user->email) }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                </div>
            </div>
        @endif

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"></li>
            <!-- Optionally, you can add icons to the links -->
            <li @yield('sidebar_home','class=""')><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>{{ trans('adminlte_lang::message.home') }}</span></a></li>
            <li @yield('sidebar_registro','class=""')><a href="{{ url('registro/registro') }}"><i class='fa fa-edit'></i> <span>Registro</span></a></li>
            <li @yield('sidebar_reporte','class=""')><a href="{{ url('reporte') }}"><i class='fa fa-file-pdf-o'></i> <span>Reporte</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
