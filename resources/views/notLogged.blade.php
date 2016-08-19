@extends('template')

@section('modules')
	, 'app.login'
@stop

@section('routeConfig')
	$urlRouterProvider.otherwise('/login');

	$stateProvider.state('app', {
    	abstract: true
    });	
@stop

@section('modulesSrc')
	<script type="text/javascript" src="modules/notLoggedIn/login/login.js"></script>
@stop