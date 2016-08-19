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
                url: '/',
                data: {'pageTitle': 'Dashboard'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/dashboard/dashboard.html',
                        controller: 'DashboardController as vm'
                    }
                }
            });
    }

    function DashboardController($http) {
        // Data
        var vm = this;

        // Methods

        ///////
    }

})();