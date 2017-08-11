(function ()
{
    'use strict';

    angular
        .module('public.welcome', [])
        .config(config)
        .controller('WelcomeController', WelcomeController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('public.welcome', {
                url: '/',
                data: {'pageTitle': 'Welcome to OMS'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/welcome/welcome.html',
                        controller: 'WelcomeController as vm'
                    }
                }
            });
    }

    function WelcomeController($http, $stateParams, $state) {
        // Data
        var vm = this;
        vm.user = {};
    }

})();