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
        vm.permissions = {
            edit_profile: true
        };
        
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

        vm.getCountries = function() {
            $http({
                method: 'GET',
                url: '/api/countries'
            })
            .then(function successCallback(response) {
                vm.countries = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getCountries();

        vm.changePicture = function() {
            alert("Not implemented");
        }

        vm.showEditProfileModal = function() {
            $('#editProfileModal').modal('show');
        }

        vm.saveProfile = function() {
            // First submit the address, then the body
            $http({
                method: 'PUT',
                url: '/api/addresses/' + vm.user.address.id,
                data: vm.user.address
            })
            .then(function successCallback(response) {
                // Create the body
                $http({
                    method: 'PUT',
                    url: '/api/users/' + vm.user.id,
                    data: vm.user
                })
                .then(function successCallback(response) {
                    // Successfully saved that body
                    $('#editProfileModal').modal('hide');
                    $.gritter.add({
                        title: 'Success',
                        text: `Successfully edited profile`,
                        sticky: false,
                        time: 8000,
                        class_name: 'my-sticky-class',
                      });
                    vm.getUser();
                }).catch(function(err) {
                    if(err.status == 422)
                        vm.errors = err.data;
                    else
                        showError(err);
                });

            }).catch(function(err) {
                if(err.status == 422)
                    vm.errors = {
                        address: err.data
                    };
                else
                    showError(err);
            });
        }
    }


    function ProfileController($http, $stateParams, $state, $scope, $sce, FileUploader) {
        // Data
        var vm = this;
        vm.user = {};
        vm.permissions = {
            edit_profile: false
        };
        
        // TODO check if own user, if yes display OwnProfileController
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