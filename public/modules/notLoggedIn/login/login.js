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
                        templateUrl: 'modules/notLoggedIn/login/login.php',
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
            $('#loadingOverlay').show();
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
                    console.log(response.data);
                    location.reload();
                } else {
                    $('#loginError').show();
                    $('#loadingOverlay').hide();
                }
            }, function errorCallback(response) {
                $.gritter.add({
                    title: 'Login error!',
                    text: 'Username / password invalid',
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
                $('#loadingOverlay').hide();
            });
        }

        ///////
    }

})();
