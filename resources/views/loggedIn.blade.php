@extends('template')

@section('modules')
	, 'app.dashboard'
@stop

@section('routeConfig')
	$urlRouterProvider.otherwise('/');

	$stateProvider.state('app', {
    	abstract: true,
    	views: {
    		'main@': {
    			templateUrl: 'template/loggedInTemplate.php'
    		},
    		'header@app': {
    			templateUrl: 'template/header.php',
                controller: "headerController as vm"
    		},
            'sidebar@app': {
                templateUrl: 'template/sidebar.php'
            }

    	}
    });
@stop

@section('modulesSrc')
	<script type="text/javascript" src="modules/loggedIn/dashboard/dashboard.js"></script>
@stop