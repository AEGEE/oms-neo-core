(function ()
{
    'use strict';

    angular
        .module('app.login', [])
        .config(config)
        .controller('LoginController', LoginController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.login', {
                url: '/',
                data: {'pageTitle': 'Login'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/oAuthLogin/oAuthLogin.php',
                        controller: 'LoginController as vm'
                    }
                }
            });
    }

    function LoginController($http) {
        // Data
        var vm = this;
        vm.user = {};

        // Methods

        vm.loginOauth = function() {
            location.href = "/oauth/login";
        }

        ///////
    }

})();
