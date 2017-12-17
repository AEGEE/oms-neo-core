(function ()
{
    'use strict';

    angular
        .module('app.admin', [])
        .config(config)
        .controller('BodyAdminController', BodyAdminController)
        .controller('AdminController', AdminController)
        .controller('CircleAdminController', CircleAdminController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.admin', {
                url: '/admin',
                data: {'pageTitle': 'Admin'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/admin/admin.html',
                        controller: 'AdminController as vm'
                    }
                }
            })
            .state('app.admin.body_types', {
                url: '/body_types',
                data: {'pageTitle': 'Body Types'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/admin/body_admin.html',
                        controller: 'BodyAdminController as vm'
                    }
                }
            })
            .state('app.admin.circles', {
                url: '/circles',
                data: {'pageTitle': 'Circles'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/admin/circle_admin.html',
                        controller: 'CircleAdminController as vm'
                    }
                }
            });
    }

    function AdminController($http) {
        var vm = this;
    }

    function CircleAdminController($http) {
        var vm = this;


        vm.showGlobalCircleModal = function(circle = undefined) {
            vm.edited_global_circle = circle;
            $('#editGlobalCircleModal').modal('show');
        }

        vm.saveGlobalCircle = function() {
            alert("Not implemented");
        }

        vm.injectParams = (params) => {
          params.name = vm.query
          params.recursive = vm.recursive;
          return params;
        }
        infiniteScroll($http, vm, '/api/circles', vm.injectParams);
    }

    function BodyAdminController($http, $scope) {
        // Data
        var vm = this;

         vm.getBodyTypes = function() {
            $http({
                method: 'GET',
                url: '/api/bodies/types'
            })
            .then(function successCallback(response) {
                vm.body_types = response.data.data;
            }).catch(function(err) {showError(err);});
        }
        vm.getBodyTypes();

        vm.showBodyTypeModal = function(bodytype = undefined) {
            vm.edited_body_type = bodytype;
            $('#editBodyTypeModal').modal('show');
        }

        vm.saveBodyType = function() {
            // If it has an id, PUT to that id, otherwise POST a new one
            var request = {};
            if(vm.edited_body_type.id) {
                request = {
                    method: 'PUT',
                    url: '/api/bodies/types/' + vm.edited_body_type.id,
                    data: vm.edited_body_type
                };
            }
            else {
                request = {
                    method: 'POST',
                    url: '/api/bodies/types/',
                    data: vm.edited_body_type
                };
            }

            $http(request)
            .then(function successCallback(response) {
                $('#editBodyTypeModal').modal('hide');
                $.gritter.add({
                    title: 'Success',
                    text: `Successfully updated bodytype`,
                    sticky: false,
                    time: 8000,
                    class_name: 'my-sticky-class',
                });
                vm.getBodyTypes();
            }).catch(function(err) {
                if(err.status == 422)
                    vm.edited_body_type_errors = err.data; // TODO might change soon
                else
                    showError(err);
            });
        }
    }

})();