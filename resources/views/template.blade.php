<?php
// Determining application environment
$isProduction = App::environment() == 'production' ? true : false;
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" ng-app="omsApp">
<!--<![endif]-->
<head>
	<base href="/">
	<meta charset="utf-8" />
	<title data-ng-bind="'{{$appName}} | ' + $state.current.data.pageTitle">{{$appName}}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="OMS" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="vendor/vendor.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="vendor/grid/css/ui.jqgrid-bootstrap.css">
	<!-- ================== END BASE CSS STYLE ================== -->
	@if(!$isProduction)
		<style type="text/css">
			#devMode {
			    display: block;
				/*position: absolute;*/
				height: 60px;
				/*width: 400px;*/
				background-color: #E91E63;
				z-index: 99998;
				box-shadow: 0 1px 5px 0 rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.12);
				color: #fff;
				top: 90%;
				right: 0.5%;
			}

			#devMessage {
				padding: 8px;
	            font-size: 16px;
			}
		</style>
	@endif
</head>
<body ng-controller="appController" ng-class="{'pace-top': setting.layout.paceTop, 'boxed-layout': setting.layout.pageBoxedLayout, 'bg-white': setting.layout.pageBgWhite }">
	<!-- begin #page-loader -->
	<!--<div id="page-loader" ng-controller="pageLoaderController" class="fade in"><span class="spinner"></span></div>-->
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="page-container page-sidebar-fixed page-header-fixed fade"
	    ng-class="{
	        'page-sidebar-minified': setting.layout.pageSidebarMinified,
	        'page-content-full-height': setting.layout.pageContentFullHeight,
	        'page-footer-fixed': setting.layout.pageFixedFooter,
	        'page-with-right-sidebar': setting.layout.pageRightSidebar,
	        'page-sidebar-minified': setting.layout.pageSidebarMinified,
	        'page-with-two-sidebar': setting.layout.pageTwoSidebar,
	        'page-right-sidebar-toggled': setting.layout.pageTwoSidebar,
	        'page-with-top-menu': setting.layout.pageTopMenu,
	        'page-without-sidebar': setting.layout.pageWithoutSidebar,
	        'page-with-wide-sidebar': setting.layout.pageWideSidebar,
	        'page-with-light-sidebar': setting.layout.pageLightSidebar,
	        'p-t-0': setting.layout.pageWithoutHeader
	    }">
	    <div id="loadingOverlay" class="hiddenItem">
		    <div class="loadingText">
		        <img src="assets/img/loading.gif" /> Loading, please wait!
		    </div>
		</div>
	    <div id="main" ui-view="main"></div>
	</div>
	<!-- end page container -->
	@if(!$isProduction)
		<div id="devMode">
			<div id="devMessage" class="text-center">
				<b>** DEBUG BUILD **</b> <br />All data is volatile and can be reverted at any time!
			</div>
		</div>
	@endif
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="vendor/vendor.js"></script>
	<!--[if lt IE 9]>
		<script src="vendor/crossbrowser.js"></script>
	<![endif]-->
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script type="text/javascript">
	    /**
	     * Main module
	     */
	    
		var omsApp = angular
	        .module('omsApp', [
	            'ui.router',
	            'ui.bootstrap',
	            'oc.lazyLoad',
	            'angularFileUpload',
	            'bootstrap3-typeahead',
	            'btford.markdown',
	            'infinite-scroll',
	            'app.dashboard',
			    'app.profile',
			    'app.news',
			    'public.login',
			    'public.signup',
			    'public.welcome',
    			{!!$modulesNames!!} 
	        ])
	        .config(appConfig)
	        .factory(responseObserver)
	        .run(appRun)
	        .service('loginModal', function ($modal, $rootScope) {

				function assignCurrentUser (user) {
					$rootScope.currentUser = user;
					return user;
				}

				return function() {
					var instance = $modal.open({
					  templateUrl: 'modules/notLoggedIn/loginModal/loginModal.html',
					  controller: 'LoginModalController as vm'
					});

					return instance.result.then(assignCurrentUser);
				};
			});
	        

	    /** @ngInject */
	    function appConfig($stateProvider, $urlRouterProvider, $locationProvider, $httpProvider)
	    {
	    	$httpProvider.interceptors.push(responseObserver);
	        $locationProvider.html5Mode(true);

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

		    	},
		    	data: {
		    		requireLogin: true
		    	}
		    })
		    .state('public', {
		        abstract: true,
		        data: {
		        	requireLogin: false
		        }
		    });
    	}

	    /** @ngInject */
	    function appRun($rootScope, $state, setting, $http, loginModal) {
	    	$rootScope.$state = $state;
		    $rootScope.setting = setting;
		    $rootScope.currentUser = undefined;

		    $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
				var requireLogin = toState.data.requireLogin;

				// On a route which requires login, check if we know user data
				if (requireLogin && typeof $rootScope.currentUser === 'undefined') {
					event.preventDefault();

					// If we still have a token, try if it's valid to fetch the user data
					var token = window.localStorage.getItem("X-Auth-Token");
				    if(token) {
				    	// We still have a token, attemp to fetch data
				    	$http({
			                method: 'POST',
			                url: '/api/tokens/user',
			                data: {
			                    token: token
			                },
			                headers: {
			                    "X-Auth-Token": token
			                }
			            }).then((response) => {
			            	// Worked, we are still logged in from the last time

			            	$http.defaults.headers.common['X-Auth-Token'] = token;
			                $rootScope.currentUser = response.data.data;
			                $state.go(toState.name, toParams)
			            }).catch((err) => {
			                // Didn't work, we need to show the login modal
			                window.localStorage.removeItem("X-Auth-Token");
			                loginModal()
							.then(function () {
								// Successful login
								return $state.go(toState.name, toParams);
							})
							.catch(function () {
								// Unsuccessful login
								return $state.go('public.welcome');
							});
				    	});
			        } else {
			        	// Otherwise we will have to log in anyways
			        	loginModal()
						.then(function () {
							// Successful login
							return $state.go(toState.name, toParams);
						})
						.catch(function () {
							// Unsuccessful login
							return $state.go('public.welcome');
						});
			        }
				}
			});
	    }

	    
	    /** @ngInject */
	    function responseObserver($q, $window, $timeout, $injector) {
	    	var loginModal, $http, $state;

			// this trick must be done so that we don't receive
			// `Uncaught Error: [$injector:cdep] Circular dependency found`
			$timeout(function () {
				loginModal = $injector.get('loginModal');
				$http = $injector.get('$http');
				$state = $injector.get('$state');
			});

		    return {
		        'responseError': function(errorResponse) {
	            	$('#loadingOverlay').hide();
		            switch (errorResponse.status) {
		            	case 401: // Trust the backend to only send this upon invalid access token

							var deferred = $q.defer();

							loginModal()
							.then(function () {
								deferred.resolve( $http(rejection.config) );
							})
							.catch(function () {
								$state.go('public.welcome');
								deferred.reject(rejection);
							});

							return deferred.promise;
			            case 403:
			                $.gritter.add({
			                    title: 'Permission error!',
			                    text: "Not enough permissions!",
			                    sticky: true,
			                    time: 8000,
			                    class_name: 'my-sticky-class'
			                });
			                break;
			            /**case 422:
			                var messages = "";
			                $.each(errorResponse.data, function(key, val) {
			                    $.each(val, function(key2, val2) {
			                        messages += "\n"+val2;
			                    });
			                });
			                $.gritter.add({
			                    title: 'Validation error!',
			                    text: "The following errors occoured:"+messages,
			                    sticky: true,
			                    time: 8000,
			                    class_name: 'my-sticky-class'
			                });
		            		break;**/
		            	case 500:
		            		$.gritter.add({
			                    title: 'Error!',
			                    text: "Please try again later",
			                    sticky: true,
			                    time: 8000,
			                    class_name: 'testClass'
			                });
		            		break;
		            }
		            return $q.reject(errorResponse);
		        }
		    };
		}

	</script>

    <script src="vendor/template.js"></script>
    
    <script type="text/javascript" src="vendor/grid/js/i18n/grid.locale-en.js"></script>
	<script type="text/javascript" src="vendor/grid/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript" src="vendor/grid/plugins/grid.postext.js"></script>
	<script type="text/javascript" src="vendor/tinymce/tinymce.min.js"></script>

	<!-- ================== END PAGE LEVEL JS ================== -->

	 @if(isset($maps_key) && $maps_key !== false)
        <!-- ================== GOOGLE MAPS KEY ================== -->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{$maps_key}}" type="text/javascript"></script>
        <!-- ================== END GOOGLE MAPS KEY ================== -->
    @endif

    <script type="text/javascript">
        
        var baseUrlRepository = {
            {!!$baseUrlRepo!!}
        };
  
    </script>
    <!--<script type="text/javascript" src="assets/js/noSessionTimeout.js"></script>-->
    <script type="text/javascript" src="modules/loggedIn/dashboard/dashboard.js"></script>
    <script type="text/javascript" src="modules/loggedIn/profile/profile.js"></script>
    <script type="text/javascript" src="modules/loggedIn/news/news.js"></script>

    @if(!$oAuthDefined)
        <script type="text/javascript" src="modules/notLoggedIn/login/login.js"></script>
        <script type="text/javascript" src="modules/notLoggedIn/loginModal/loginModal.js"></script>
    @else
        <script type="text/javascript" src="modules/notLoggedIn/oAuthLogin/oAuthLogin.js"></script>
    @endif
    <script type="text/javascript" src="modules/notLoggedIn/signup/signup.js"></script>
    <script type="text/javascript" src="modules/notLoggedIn/welcome/welcome.js"></script>

    {!!$modulesSrc!!}
</body>
</html>
