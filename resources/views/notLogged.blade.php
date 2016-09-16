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
	<script type="text/javascript" src="modules/notLoggedIn/login/login.js"></script>
	<script type="text/javascript" src="modules/notLoggedIn/signup/signup.js"></script>
@stop