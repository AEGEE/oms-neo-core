(function ()
{
    'use strict';

    angular
        .module('public.login', [])
        .config(config)
        .controller('LoginController', LoginController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('public.login', {
                url: '/login',
                params: {
                    redirect: null,
                    redirect_params: null
                },
                data: {'pageTitle': 'Login'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/login/login.php',
                        controller: 'LoginController as vm'
                    }
                }
            });
    }

    function LoginController($http, $stateParams, $state) {
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
                    // Store in local storage
                    window.localStorage.setItem("X-Auth-Token", response.data.data);
                    $http({
                        method: 'POST',
                        url: '/api/tokens/user',
                        data: {
                            token: localStorage.getItem("X-Auth-Token")
                        },
                        headers: {
                            "X-Auth-Token": localStorage.getItem("X-Auth-Token")
                        }
                    })
                    .then(function successCallback(response) {
                        $rootScope.currentUser = response.data.data;
                    }).catch(function(err) {
                        $state.go('public.login');
                    });
                    
                    $('#loadingOverlay').hide();
                    if($stateParams.redirect != null) {
                        console.log("Logged in, redirecting to ", $stateParams.redirect);
                        $state.go($stateParams.redirect, $stateParams.redirect_params);
                    }
                    else {
                        console.log("Logged in, going to start page");
                        $state.go('app.dashboard');
                    }
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