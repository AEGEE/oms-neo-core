@extends('template')

<?php 
// $modulesCodes = "";
// $modulesSrc = "";

// foreach($modules as $key => $value) {
//     foreach($value as $singleModule) {
//         $modulesCodes .= ", 'app.".$singleModule['code']."'";
//         $modulesSrc .= "<script type='text/javascript' src='".$singleModule['link']."'></script>";
//     }
// }
    
?>

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
	<script type="text/javascript" src="modules/loggedIn/dashboard/dashboard.js"></script>
    {!!$modulesSrc!!}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {'X-Auth-Token': '{!!$authToken!!}'}
        });
        var countries = {
            {!!$countries!!}
        }
    </script>
@stop