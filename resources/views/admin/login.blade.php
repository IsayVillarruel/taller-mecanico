<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login - {{ env("APP_NAME") }}</title>
	
	{{--<link rel="stylesheet" type="text/css" href="{{  asset(mix("assets/admin/js/plugins/fontawesome-free/css/all.min.css")) }}">--}}
	<link rel="stylesheet" type="text/css" href="{{  asset(mix("assets/admin/css/bundle.css")) }}">
	<link rel="stylesheet" type="text/css" href="{{  asset("assets/admin/js/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
	<link rel="stylesheet" type="text/css" href="{{  asset(mix("assets/admin/css/App.css")) }}">
	

	
		

	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Google Font -->
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<body class="sidebar-mini layout-fixed">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-blue navbar-dark">
		    <!-- Left navbar links -->
		    <ul class="navbar-nav">
		      <li class="nav-item">
		        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		      </li>
		      <li class="nav-item d-none d-sm-inline-block">
		        <a href="{{ URL::to("/") }}" class="nav-link">Home</a>
		      </li>
		     
		    </ul>

		    
		</nav>
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
		    <!-- Brand Logo -->
		    <a href="index3.html" class="brand-link modified">
			<!-- IMG -->
		      <span class="brand-text font-weight-light mx-auto">{{ env("APP_NAME") }}</span>
		    </a>

		    <!-- Sidebar -->
		    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 587px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible os-viewport-native-scrollbars-overlaid" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
		      <!-- Sidebar user panel (optional) -->


		      <!-- Sidebar Menu -->
		      <nav class="mt-2">
		       
		      </nav>
		      <!-- /.sidebar-menu -->
		    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 46.7409%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
		    <!-- /.sidebar -->
		</aside>
		<div class="content-wrapper" style="min-height: 622px;">
		    <!-- Content Header (Page header) -->
		    <div class="content-header">
		      <div class="container-fluid">
		        <div class="row mb-2">
		          <div class="col-sm-6">
		            <h1 class="m-0 text-dark">Login</h1>
		          </div><!-- /.col -->
		        </div><!-- /.row -->
		      </div><!-- /.container-fluid -->
		    </div>
		    <!-- /.content-header -->

		    <!-- Main content -->
		    <section class="content">
		      <div class="container-fluid">
		        <!-- Small boxes (Stat box) -->
		        <div class="row">
		        	<div class="col-3">
		          	
		        	</div>
		        	<div class="col">
		         		<div class="card card-info">
		              		<div class="card-header">
		                		<h3 class="card-title">Login Form</h3>
		              		</div>
		            		<!-- /.card-header -->
		            		<!-- form start -->
				            <form class="form-horizontal" method="post" id="login-form">
				            	@csrf
				                <div class="card-body">
				                  <div class="form-group row">
				                    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
				                    <div class="col-sm-10">
				                      <input type="email" class="form-control" name="email" id="inputEmail3" placeholder="Email" required>
				                    </div>
				                  </div>
				                  <div class="form-group row">
				                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
				                    <div class="col-sm-10">
				                      <input type="password" class="form-control" name="password" id="inputPassword3" placeholder="Password" required>
				                    </div>
				                  </div>
				                </div>
				                <!-- /.card-body -->
				                <div class="card-footer">
				                  <button type="submit" class="btn btn-info">Entrar</button>
				                  <button type="button" class="btn btn-default float-right">Cancelar</button>
				                </div>
				                <!-- /.card-footer -->
				            </form>
		        		</div>
		        	</div>
		        	<div class="col-3">
		          	
		        	</div>
		        </div>
		        <!-- /.row -->
		        <!-- Main row -->
		        <div class="row">
		        </div>
		        <!-- /.row (main row) -->
		      </div><!-- /.container-fluid -->
		    </section>
		    <!-- /.content -->
		  </div>
		@include('admin.footer')

	</div>

	<script src="{{ URL::asset("assets/admin/js/bundle.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") }}"></script>
	<script src="{{ URL::asset("assets/admin/js/App.js") }}"></script>

	<script>
		$(function(){

			$("#login-form").submit(function(event)
    		{
    			event.preventDefault();
    			$.ajax({
    				url:$("#login-form").attr('action'),
    				method:'post',
    				async :true,
    				data:$("#login-form").serialize(),
    			}).done(function(data){

    				

                    if(data.type=="success")
                    {
                        alertify.success('Acceso concedido');
                        document.location=data.url;
                    }
                    else
                    {
                        alertify.error(data.message);
                    }
                    

    			}).fail(function(data){
                    alertify.set('notifier','position', 'top-right');
    				alertify.error('Tu usuario o password son erroneos');
    			});

    		});

		});

	</script>		
</body>
</html>