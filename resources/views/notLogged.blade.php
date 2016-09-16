@extends('template')

@section('modules')
	, 'app.login'
	, 'app.signup'
@stop

@section('routeConfig')
	$urlRouterProvider.otherwise('/');

	$stateProvider.state('app', {
    	abstract: true
    });	
@stop

@section('modulesSrc')
	@if(!$oAuthDefined)
		<script type="text/javascript" src="modules/notLoggedIn/login/login.js"></script>
	@else
		<script type="text/javascript" src="modules/notLoggedIn/oAuthLogin/oAuthLogin.js"></script>
	@endif
	<script type="text/javascript" src="modules/notLoggedIn/signup/signup.js"></script>
@stop