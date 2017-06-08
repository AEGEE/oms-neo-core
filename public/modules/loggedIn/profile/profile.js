(function ()
{
    'use strict';

    angular
        .module('app.profile', [])
        .config(config)
        .controller('ProfileController', ProfileController)
        .controller('OwnProfileController', OwnProfileController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.own_profile', {
                url: '/profile/',
                data: {'pageTitle': 'My Profile'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/profile/profile.html',
                        controller: 'OwnProfileController as vm'
                    }
                }
            })
            .state('app.profile', {
                url: '/profile/{id}',
                data: {'pageTitle': 'Profile'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/profile/profile.html',
                        controller: 'ProfileController as vm'
                    }
                }
            });
    }


    function OwnProfileController($http, $stateParams, $state, $scope, $sce, FileUploader) {
        // Data
        var vm = this;
        vm.user = {};
        
        vm.getUser = function() {
            $http({
                method: 'POST',
                url: '/api/tokens/user',
                data: {
                    token: localStorage.getItem("X-Auth-Token")
                }
            })
            .then(function successCallback(response) {
                vm.user = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getUser();
    }


    function ProfileController($http, $stateParams, $state, $scope, $sce, FileUploader) {
        // Data
        var vm = this;
        vm.user = {};
        
        vm.getUser = function() {
            $http({
                method: 'GET',
                url: '/api/users/' + $stateParams.id,
            })
            .then(function successCallback(response) {
                vm.user = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getUser();
    }

})();