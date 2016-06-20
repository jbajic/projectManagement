<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="_token" content="{!! csrf_token() !!}" />
		<title>Project Management</title>

		<!-- STYLES -->
		<!-- <link href="/css/jquery-ui.min.css" rel="stylesheet" /> -->
		<link href="/css/bootstrap-3.3.6-dist/bootstrap.min.css" rel="stylesheet" />
		<link href="/css/sweetalert2.min.css" rel="stylesheet" />
		<link href="/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
		<link href="/css/dragula.min.css" rel="stylesheet" />
		<link href="/css/awesome-bootstrap-checkbox.css" rel="stylesheet" />

		<!-- ICONS -->
		<link href="/font-awesome-4.6.1/css/font-awesome.min.css" rel="stylesheet" />

		<!-- CUSTOM STYLES -->
		<link href="/css/custom/main.css" rel="stylesheet" />
		<link href="/css/custom/autocomplete.css" rel="stylesheet" />
		<link href="/css/custom/project.css" rel="stylesheet" />
		<link href="/css/custom/showProject.css" rel="stylesheet" />

		@yield('styles')

	</head>
	<body>

		@include('includes.navigation')

		@yield('content')
			
	<!--  JAVASCRIPT BOOSTRAP & JQUERY -->
	<script src="/js/jquery-2.2.3.min.js"></script>
	<script src="/js/bootstrap-3.3.6-dist/bootstrap.min.js"></script>
	<script src="/js/jquery.noty.packaged.min.js"></script>
	<script src="/js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<script src="/js/notify.min.js"></script>
	<script src="/js/sweetalert2.js"></script>
	<script src="/js/bootstrap-3.3.6-dist/bootstrap-filestyle.min.js"></script>
	<script src="/js/bootstrap-datepicker.min.js"></script>
	<script src="/js/dragula.min.js"></script>

	<!-- CUSTOM SCRIPTS					-->
	<script src="/js/custom/setup.js"></script>
	<script src="/js/custom/messages.js"></script>
	<script src="/js/custom/functions.js"></script>
	<script src="/js/custom/main.js"></script>
	<script src="/js/custom/profile.js"></script>
	<script src="/js/custom/addProject.js"></script>
	<script src="/js/custom/showProject.js"></script>

	@yield('scripts')
	</body>
</html>