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

        vm.query = "";

        vm.querytoken = 0;

        vm.getBodies = function() {
            var active_types = vm.body_types
                .filter(function(item) {return item.filter_active;})
                .map(function(item) {return item.id});

            vm.querytoken += 1;
            var mytoken = vm.querytoken;

            $http({
                method: 'GET',
                url: '/api/bodies',
                params: {
                    "name": vm.query,
                    "body_id": active_types
                }
            })
            .then(function successCallback(response) {
                if(mytoken == vm.querytoken) // Make sure no request has surpassed us
                    vm.bodies = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getBodies();

        vm.getBodyTypes = function() {
            $http({
                method: 'GET',
                url: '/api/bodies/types'
            })
            .then(function successCallback(response) {
                vm.body_types = response.data.data.map(function(cur) {cur.filter_active=true; return cur;});
            }).catch(function(err) {showError(err);});
        }
        vm.getBodyTypes();

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

        vm.saveBodyForm = function() {
            // First create the address object, then the body
            $http({
                method: 'POST',
                url: '/api/addresses',
                data: vm.body.address
            })
            .then(function successCallback(response) {
                vm.body.address = response.data.data;
                vm.body.address_id = vm.body.address.id;
                // Create the body
                $http({
                    method: 'POST',
                    url: '/api/bodies',
                    data: vm.body
                })
                .then(function successCallback(response) {
                    // Successfully saved that body
                    $('#editBodyModal').modal('hide');
                    $.gritter.add({
                        title: 'Success',
                        text: `Successfully added body`,
                        sticky: false,
                        time: 8000,
                        class_name: 'my-sticky-class',
                      });
                    vm.getBodies();
                }).catch(function(err) {
                    if(err.status == 422)
                        vm.errors = err.data.errors;
                    else
                        showError(err);
                });

            }).catch(function(err) {
                if(err.status == 422)
                    vm.errors = err.data.errors;
                else
                    showError(err);
            });
        };

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
            }).catch(function(err) {showError(err);});
        }

        vm.getBodyAddress = function(id) {
            $http({
                method: 'GET',
                url: '/api/addresses/' + id
            })
            .then(function successCallback(response) {
                vm.body.address = response.data.data;
            }).catch(function(err) {showError(err);});
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
            }).catch(function(err) {showError(err);});
        };
        vm.getBody($stateParams.id);

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

        vm.saveBodyForm = function() {
            // First submit the address, then the body
            $http({
                method: 'PUT',
                url: '/api/addresses/' + vm.body.addess.id,
                data: vm.body.address
            })
            .then(function successCallback(response) {
                // Create the body
                $http({
                    method: 'PUT',
                    url: '/api/bodies/' + vm.body.id,
                    data: vm.body
                })
                .then(function successCallback(response) {
                    // Successfully saved that body
                    $('#editBodyModal').modal('hide');
                    $.gritter.add({
                        title: 'Success',
                        text: `Successfully edited body`,
                        sticky: false,
                        time: 8000,
                        class_name: 'my-sticky-class',
                      });
                    vm.getBodies();
                }).catch(function(err) {
                    if(err.status == 422)
                        vm.errors = err.data.errors;
                    else
                        showError(err);
                });

            }).catch(function(err) {
                if(err.status == 422)
                    vm.errors = err.data.errors;
                else
                    showError(err);
            });
        };

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

})();