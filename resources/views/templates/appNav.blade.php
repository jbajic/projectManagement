<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="_token" content="{!! csrf_token() !!}" />
		<title>Project Management</title>

		<!-- STYLES - BOOSTRAP -->
		<link href="/css/bootstrap-3.3.6-dist/bootstrap.min.css" rel="stylesheet" />
		<link href="/css/sweetalert2.min.css" rel="stylesheet" />

		<!-- ICONS -->
		<link href="/font-awesome-4.6.1/css/font-awesome.min.css" rel="stylesheet" />

		<!-- CUSTOM STYLES -->
		<link href="/css/main.css" rel="stylesheet" />
		<link href="/css/autocomplete.css" rel="stylesheet" />

		@yield('styles')

	</head>
	<body>
		<div class="container-fluid">

			@include('includes.navigation')

			@yield('content')
			
		</div>
	<!--  JAVASCRIPT BOOSTRAP & JQUERY -->
	<script src="/js/jquery-2.2.3.min.js"></script>
	<script src="/js/bootstrap-3.3.6-dist/bootstrap.min.js"></script>
	<script src="/js/jquery.noty.packaged.min.js"></script>
	<script src="/js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<script src="/js/notify.min.js"></script>
	<script src="/js/sweetalert2.js"></script>
	<script src="/js/bootstrap-3.3.6-dist/bootstrap-filestyle.min.js"></script>
	<script src="/js/custom/setup.js"></script>
	<script src="/js/custom/main.js"></script>
	<script src="/js/custom/profile.js"></script>

	@yield('scripts')
	</body>
</html>