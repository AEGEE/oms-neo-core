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
	<title data-ng-bind="'OMS | ' + $state.current.data.pageTitle">OMS</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="OMS" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
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
		            'oc.lazyLoad'     
		            @yield('modules')  
		        ])
		        .config(routeConfig)
		        .run(['$rootScope', '$state', 'setting', '$http', function($rootScope, $state, setting, $http) {
				    $rootScope.$state = $state;
				    $rootScope.setting = setting;
				    @yield('otherConfig')
				}]);
		        

		    /** @ngInject */
		    function routeConfig($stateProvider, $urlRouterProvider, $locationProvider)
		    {
		        $locationProvider.html5Mode(true);

		        @yield('routeConfig')
		    }
	</script>

    <script src="vendor/template.js"></script>
    
    <script type="text/javascript" src="vendor/grid/js/i18n/grid.locale-en.js"></script>
	<script type="text/javascript" src="vendor/grid/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript" src="vendor/grid/plugins/grid.postext.js"></script>
	<script type="text/javascript" src="vendor/tinymce/tinymce.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	@yield('modulesSrc')
</body>
</html>
