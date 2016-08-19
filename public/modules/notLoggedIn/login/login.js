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
                url: '/login',
                data: {'pageTitle': 'Login'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/login/login.html',
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

        vm.login = function() {
            $http({
                method: 'POST',
                url: '/api/login',
                data: {
                    username: vm.user.username,
                    password: vm.user.password
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == 1) {
                    location.reload();
                } else {
                    $('#loginError').show();
                }
            }, function errorCallback(response) {
                $('#loginError').show();
            });
        }

        ///////
    }

})();