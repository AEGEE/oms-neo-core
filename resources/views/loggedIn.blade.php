@extends('template')



@section('modules')
    , 'app.dashboard'
    , 'app.profile'
    , 'app.news'
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
    @if(isset($maps_key) && $maps_key !== false)
        <!-- ================== GOOGLE MAPS KEY ================== -->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{$maps_key}}" type="text/javascript"></script>
        <!-- ================== END GOOGLE MAPS KEY ================== -->
    @endif

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
        var isSuperAdmin = {{$userData['is_superadmin'] == 1 ? 'true' : 'false' }};
        var xAuthToken = '{!!$authToken!!}';

        var moduleAccess = {
            {!!$moduleAccess!!}
        };

        @if(isset($suspention))
            var suspendedFor = "{{$suspention}}";
            var suspended = true;
        @else
            var suspended = false;
        @endif    
    </script>
    <script type="text/javascript" src="assets/js/noSessionTimeout.js"></script>
    <script type="text/javascript" src="modules/loggedIn/dashboard/dashboard.js"></script>
    <script type="text/javascript" src="modules/loggedIn/profile/profile.js"></script>
    <script type="text/javascript" src="modules/loggedIn/news/news.js"></script>
    {!!$modulesSrc!!}
@stop