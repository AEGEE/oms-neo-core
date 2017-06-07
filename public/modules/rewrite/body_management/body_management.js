(function ()
{
    'use strict';

    angular
        .module('app.body_management', [])
        .config(config)
        .directive('bodytile', TileDirective)
        .controller('BodyListingController', BodyListingController)
        .controller('BodySingleController', BodySingleController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.body_management', {
                url: '/body_management',
                data: {'pageTitle': 'All Bodies'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/body_management/list.html',
                        controller: 'BodyListingController as vm'
                    }
                }
            })
            .state('app.body_management.single', {
                url: '/body_management/:id',
                data: {'pageTitle': 'Body Details'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/body_management/single.html',
                        controller: 'BodySingleController as vm'
                    }
                }
            });
    }

    function TileDirective() {
        return {
            restrict: 'E',
            scope: {
                body: '='
            },
            templateUrl: 'modules/rewrite/body_management/directive_bodytile.html'
        };
    }

    function BodyListingController($http, $scope, $stateParams) {
        // Data
        var vm = this;

        // TODO replace with real fetch from backend
        vm.permissions = {
            create_body: true
        };

        vm.getBodies = function() {
            $http({
                method: 'GET',
                url: '/api/bodies'
            })
            .then(function successCallback(response) {
                vm.bodies = response.data.data;
            })
            .catch(function(err) {
                showError(err);
            });
        }
        vm.getBodies();

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

    function BodySingleController($http, $scope, $stateParams) {
        var vm = this;

        vm.permissions = {
            edit_body: true,
            edit_circles: true
        };

        vm.getBodyType = function(id) {
            $http({
                method: 'GET',
                url: '/api/bodies/types/' + id
            })
            .then(function successCallback(response) {
                vm.body.type = response.data.data;
            })
            .catch(function(err) {
                showError(err);
            });
        }

        vm.getBodyAddress = function(id) {
            $http({
                method: 'GET',
                url: '/api/addresses/' + id
            })
            .then(function successCallback(response) {
                vm.body.address = response.data.data;
            })
            .catch(function(err) {
                showError(err);
            });
        }

        vm.getBody = function(id) {
            $http({
                method: 'GET',
                url: '/api/bodies/' + id
            })
            .then(function successCallback(response) {
                vm.body = response.data.data[0];
                vm.getBodyType(vm.body.type_id);
                vm.getBodyAddress(vm.body.address_id);
            })
            .catch(function(err) {
                showError(err);
            });
        };
        vm.getBody($stateParams.id);

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

})();