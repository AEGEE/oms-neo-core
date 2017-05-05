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

        vm.body_types = [
        {
            name: "Contact"
        }, {
            name: "Contact Antenna"
        }, {
            name: "Full Antenna"
        }
        ];

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
            alert("Not implemented");
        }

        vm.saveGlobalCircle = function() {
            alert("Not implemented");
        }
    }

})();