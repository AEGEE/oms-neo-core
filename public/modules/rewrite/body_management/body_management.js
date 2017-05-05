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
                url: '/body_management/single',
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

    function BodyListingController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;

        // TODO replace with real fetch from backend
        vm.permissions = {
            create_body: true
        };

        vm.bodies = [
            {
                title: "AEGEE-Dresden",
                subtitle: "The best local ever"
            }, {
                title: "ITC",
                subtitle: "Nerds and stuff"
            }
        ];

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

    function BodySingleController() {
        var vm = this;

        vm.permissions = {
            edit_body: true,
            edit_circles: true
        };

        vm.body = {
            title: "AEGEE-Dresden",
            subtitle: "The best local ever",
            circles: [
                {
                    name: "Members",
                    users: [
                        {
                            name: "Nico",
                        }, {
                            name: "Dresdino"
                        }
                    ]
                },
                {
                    name: "Board",
                    users: [
                        {
                            name: "Nico"
                        }
                    ]
                }
            ]
        }

        vm.showBodyModal = function() {
            $('#editBodyModal').modal('show');
        }
    }

})();