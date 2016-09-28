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
        vm.users = 0;
        vm.newestMembers = {};

        vm.suspended = suspended;
        if(vm.suspended) {
            vm.suspendedFor = suspendedFor;
        }

        // Methods
        vm.getDashboardData = function() {
            $('#loadingOverlay').show();
            $http({
                method: 'GET',
                url: "/api/getDashboardData"
            }).then(function successCallback(response) {
                vm.users = response.data.userCount;
                vm.newestMembers = response.data.newestMembers;
                $('#loadingOverlay').hide();
            });
        }

        ///////
        vm.getDashboardData();
    }

})();