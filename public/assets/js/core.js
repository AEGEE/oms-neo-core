(function ()
{
    'use strict';

    /**
     * Main module
     */
    angular
        .module('oms', [
            'ui.router',
            'ui.bootstrap',
            'oc.lazyLoad'       
        ])
        .controller('CoreController', CoreController)
        .config(routeConfig);
        

    /** @ngInject */
    function CoreController()
    {
        var vm = this;

        // Data

        //////////

    }

    /** @ngInject */
    function routeConfig($stateProvider, $urlRouterProvider, $locationProvider)
    {
        $locationProvider.html5Mode(true);

        $urlRouterProvider.otherwise('/login');
    }
})();