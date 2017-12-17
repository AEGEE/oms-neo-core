(function ()
{
    'use strict';

    angular
        .module('app.dashboard', [])
        .config(config)
        .controller('DashboardController', DashboardController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.dashboard', {
                url: '/dashboard',
                data: {'pageTitle': 'Dashboard'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/dashboard/dashboard.html',
                        controller: 'DashboardController as vm'
                    }
                }
            });
    }

    function DashboardController($http, $state) {
        // Data
        var vm = this;

    }

})();