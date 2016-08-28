@extends('template')

@section('modules')
	, 'app.dashboard'
    {!!$modulesNames!!}
    
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

@section('otherConfig')
    $http.defaults.headers.common['X-Auth-Token'] = '{!!$authToken!!}';
@stop

@section('modulesSrc')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {'X-Auth-Token': '{!!$authToken!!}'}
        });
        var countries = {
            {!!$countries!!}
        };
        var baseUrlRepository = {
            {!!$baseUrlRepo!!}
        };
    </script>
    <script type="text/javascript" src="assets/js/noSessionTimeout.js"></script>
    <script type="text/javascript" src="modules/loggedIn/dashboard/dashboard.js"></script>
    {!!$modulesSrc!!}
@stop