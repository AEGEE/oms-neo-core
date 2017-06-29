(function ()
{
    'use strict';

    angular
        .module('app.body_admin', [])
        .config(config)
        .controller('BodyAdminController', BodyAdminController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.body_admin', {
                url: '/body_admin',
                data: {'pageTitle': 'Body Admin'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/body_admin/body_admin.html',
                        controller: 'BodyAdminController as vm'
                    }
                }
            });
    }

    function BodyAdminController($http, $compile, $scope, $window, $httpParamSerializer) {
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

        vm.global_circles = [
            {
                name: "Board"
            }, {
                name: "Member"
            }
        ];

        vm.showBodyTypeModal = function(bodytype = undefined) {
            vm.edited_body_type = bodytype;
            $('#editBodyTypeModal').modal('show');
        }

        vm.showGlobalCircleModal = function(circle = undefined) {
            vm.edited_global_circle = circle;
            $('#editGlobalCircleModal').modal('show');
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

        vm.saveGlobalCircle = function() {
            alert("Not implemented");
        }
    }

})();