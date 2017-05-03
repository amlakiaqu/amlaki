@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="https://placehold.it/160x160/ffffff/3c8dbc/&text={{ mb_substr(Auth::user()->name, 0, 2) }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">{{ trans('backpack::base.administration') }}</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}">{{ trans('backpack::base.dashboard') }}</a></li>
{{--          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/elfinder') }}">File manager</a></li>--}}
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}">{{ trans('Logs')  }}</a></li>

          <li class="header">{{ trans('Management') }}</li>
          <li><a href="{{ url('admin/users') }}">{{ trans('Users') }}</a></li>
          <li><a href="{{ url('admin/posts') }}">{{ trans('Posts') }}</a></li>
          <li><a href="{{ url('admin/post_property') }}">{{ trans('Posts Properties')  }}</a></li>
          <li><a href="{{ url('admin/categories') }}">{{ trans('Categories')  }}</a></li>
          <li><a href="{{ url('admin/category_property') }}">{{ trans('Categories Properties')  }}</a></li>
          <li><a href="{{ url('admin/requests') }}">{{ trans('Requests')  }}</a></li>
          <li><a href="{{ url('admin/request_property') }}">{{ trans('Requests Properties')  }}</a></li>

          <!-- ======================================= -->
{{--          <li class="header">{{ trans('backpack::base.user') }}</li>--}}
{{--          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>--}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
