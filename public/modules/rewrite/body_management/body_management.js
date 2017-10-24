(function ()
{
    'use strict';

    angular
        .module('app.body_management', [])
        .config(config)
        .directive('bodytile', TileDirective)
        .controller('BodyListingController', BodyListingController)
        .controller('BodySingleController', BodySingleController);

    const baseUrl = baseUrlRepository['oms-core'];

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
                        templateUrl: baseUrl + 'modules/rewrite/body_management/list.html',
                        controller: 'BodyListingController as vm'
                    }
                }
            })
            .state('app.body_management.single', {
                url: '/body_management/:id',
                data: {'pageTitle': 'Body Details'},
                views   : {
                    'pageContent@app': {
                        templateUrl: baseUrl + 'modules/rewrite/body_management/single.html',
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
            templateUrl: baseUrl + 'modules/rewrite/body_management/directive_bodytile.html'
        };
    }

    function BodyListingController($http, $scope, $stateParams) {
        // Data
        var vm = this;

        vm.formInclude = baseUrl + 'modules/rewrite/body_management/edit_body_form.html';
        // TODO replace with real fetch from backend
        vm.permissions = {
            create_body: true
        };

        vm.query = "";
        
        vm.injectParams = (params) => {
            params.name = vm.query
            return params;
        }
        infiniteScroll($http, vm, baseUrl + 'api/bodies', vm.injectParams);


        vm.body = {};
        vm.body_types = [];
        vm.querytoken = 0;

        vm.getBodyTypes = function() {

            $http({
                method: 'GET',
                url: baseUrl + 'api/bodies/types'
            })
            .then(function successCallback(response) {
                vm.body_types = response.data.data.map(function(cur) {cur.filter_active=true; return cur;});
            }).catch(function(err) {showError(err);});
        }
        vm.getBodyTypes();

        vm.getCountries = function() {
            $http({
                method: 'GET',
                url: baseUrl + 'api/countries'
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
                url: baseUrl + 'api/addresses',
                data: vm.body.address
            })
            .then(function successCallback(response) {
                vm.body.address = response.data.data;
                vm.body.address_id = vm.body.address.id;
                // Create the body
                $http({
                    method: 'POST',
                    url: baseUrl + 'api/bodies',
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
        };

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

    function BodySingleController($http, $scope, $stateParams) {
        var vm = this;

        vm.formInclude = baseUrl + 'modules/rewrite/body_management/edit_body_form.html';

        vm.permissions = {
            edit_body: true,
            edit_circles: true,
            request_join: true
        };
        vm.body = {};
        vm.countries = [];
        vm.body_types = [];

        vm.getBody = function(id) {
            $http({
                method: 'GET',
                url: baseUrl + 'api/bodies/' + id
            })
            .then(function successCallback(response) {
                vm.body = response.data.data;

            }).catch(function(err) {showError(err);});
        };
        vm.getBody($stateParams.id);

        vm.getCountries = function() {
            $http({
                method: 'GET',
                url: baseUrl + 'api/countries'
            })
            .then(function successCallback(response) {
                vm.countries = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getCountries();

        vm.getBodyTypes = function() {

            $http({
                method: 'GET',
                url: baseUrl + 'api/bodies/types'
            })
            .then(function successCallback(response) {
                vm.body_types = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getBodyTypes();

        vm.saveBodyForm = function() {
            // First submit the address, then the body
            $http({
                method: 'PUT',
                url: baseUrl + 'api/addresses/' + vm.body.address.id,
                data: vm.body.address
            })
            .then(function successCallback(response) {
                // Create the body
                $http({
                    method: 'PUT',
                    url: baseUrl + 'api/bodies/' + vm.body.id,
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
                    vm.getBody($stateParams.id);
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
        };

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

})();