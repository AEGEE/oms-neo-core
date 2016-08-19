<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" ng-app="omsApp">
<!--<![endif]-->
<head>
	<base href="/">
	<meta charset="utf-8" />
	<title data-ng-bind="'OMS | ' + $state.current.data.pageTitle">OMS</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="OMS" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
    
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!--[if lt IE 9]>
	    <script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	
	<!-- ================== BEGIN BASE ANGULAR JS ================== -->
    <script src="assets/plugins/angularjs/angular.min.js"></script>
    <script src="assets/plugins/angularjs/angular-ui-route.min.js"></script>
    <script src="assets/plugins/bootstrap-angular-ui/ui-bootstrap-tpls.min.js"></script>
    <script src="assets/plugins/angularjs/ocLazyLoad.min.js"></script>
	<!-- ================== END BASE ANGULAR JS ================== -->
</head>
<body ng-controller="appController" ng-class="{'pace-top': setting.layout.paceTop, 'boxed-layout': setting.layout.pageBoxedLayout, 'bg-white': setting.layout.pageBgWhite }">
    
	<!-- begin #page-loader -->
	<div id="page-loader" ng-controller="pageLoaderController" class="fade in"><span class="spinner"></span></div>
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
	    <div id="main" ui-view="main"></div>
	</div>
	<!-- end page container -->
	
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
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
		            'oc.lazyLoad'     
		            @yield('modules')  
		        ])
		        .config(routeConfig)
		        .run(['$rootScope', '$state', 'setting', function($rootScope, $state, setting) {
				    $rootScope.$state = $state;
				    $rootScope.setting = setting;
				}]);
		        

		    /** @ngInject */
		    function routeConfig($stateProvider, $urlRouterProvider, $locationProvider)
		    {
		        $locationProvider.html5Mode(true);

		        @yield('routeConfig')

		        
		    }
	</script>

    <!-- <script src="assets/js/angular-app.js"></script> -->
    <script src="assets/js/angular-setting.js"></script>
    <script src="assets/js/angular-controller.js"></script>
    <script src="assets/js/angular-directive.js"></script>
    <script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	@yield('modulesSrc')
</body>
</html>
