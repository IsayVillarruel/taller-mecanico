<!DOCTYPE html>
<html>
<head>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Google Font -->
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	
	<link rel="stylesheet" type="text/css" href="{{  asset(mix("assets/admin/css/bundle.css")) }}">
	<link rel="stylesheet" type="text/css" href="{{  asset("assets/admin/js/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
	
	@hasSection('styles')
	   @yield('styles')
	@endif

	<link rel="stylesheet" type="text/css" href="{{ URL::asset("assets/admin/css/App.css") }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Admin {{ env("APP_NAME") }} - @yield('title')</title>
</head>
<body class="sidebar-mini layout-fixed">
	
	<div class="wrapper">
		{{-- Nav Bar  --}}
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="{{ route("adminDashBoard") }}" class="nav-link">Home</a>
				</li>
				
			</ul>

			
		</nav>

		{{-- /Nav Bar  --}}
		
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="{{ url("/admin/dash-board") }}" class="brand-link modified">
				<!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
				<span class="brand-text font-weight-light longer mx-auto">{{ env("APP_NAME")}}<br>Admin</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 585px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible os-viewport-native-scrollbars-overlaid" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-2 d-flex">
					
					<div class="info">
						<a href="#" class="d-block"><i class="fa fa-user"></i>  {{ Auth::User()->name }}</a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class
							with font-awesome or any other icon font library -->
						<li class="nav-header">Módulos</li>
						
						@php
							$perms=json_decode(Auth::User()->perm);
							$idModules=array();

							foreach($perms as $item)
							{
								$idModules[]=$item->idModule;
							}
						@endphp
						
						@forelse(App\Models\Module::whereIn("id",$idModules)->where("main_menu",1)->get() as $moduleWithPerm)
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon {{ $moduleWithPerm->class_icon }}"></i>
									<p>
										{{ $moduleWithPerm->title }}
										<i class="fas fa-angle-left right"></i>
										
									</p>
								</a>
								<ul class="nav nav-treeview">
									@if(Auth::User()->hasPermIntern($moduleWithPerm->id,"list"))
										<li class="nav-item">
											<a href="{{ URL::to("admin/".$moduleWithPerm->name) }}" class="nav-link">
												<i class="fas nav-icon fa-list"></i>
												<p>Lista</p>
											</a>
										</li>
									@endif
									@if(Auth::User()->hasPermIntern($moduleWithPerm->id,"new"))
										<li class="nav-item">
											<a href="{{ URL::to("admin/".$moduleWithPerm->name."/add") }}" class="nav-link">
												<i class="fas fa-plus-square nav-icon"></i>
												<p>Nuevo</p>
											</a>
										</li>
									@endif
								</ul>
							</li>
						@empty

						@endforelse
						<li class="nav-item">
							<a href="{{ url("admin/logout") }}" class="nav-link">
									<i class="nav-icon fas fa-sign-out-alt"></i>
									<p>
										Cerrar sesión
										
									</p>
							</a>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
				</div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 46.5819%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
				<!-- /.sidebar -->
			</aside>

			<div class="content-wrapper" style="min-height: 542px;">
				<!-- Content Header (Page header) -->
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0 text-dark">@yield("title_module")</h1>
							</div><!-- /.col -->
							<div class="col-sm-6">
								@yield("breadcrumb")
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.container-fluid -->
				</div>
				<!-- /.content-header -->

				<!-- Main content -->
				<section class="content">
					@yield("primary_content")
						
				</section>
					

					
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	
	@include('admin.footer')
	

	

  	<div id="sidebar-overlay"></div>
  	<script>
  		
  		var baseWebSiteURL="{{ URL::to("/") }}";
  		
  	</script>

	<script src="{{ URL::asset("assets/admin/js/bundle.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") }}"></script>
	

	@hasSection('scripts')
	   @yield('scripts')
	@endif


	<script src="{{ URL::asset("assets/admin/js/App.js") }}"></script>
	

</body>
</html>