(function ()
{
    'use strict';

    angular
        .module('app.my_bodies', [])
        .config(config)
        .directive('circlepanel', CircleDirective)
        .controller('MyBodiesController', MyBodiesController)
        .controller('EditCirclesController', EditCirclesController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.my_bodies', {
                url: '/my_bodies',
                data: {'pageTitle': 'My Bodies'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/my_bodies/my_bodies.html',
                        controller: 'MyBodiesController as vm'
                    }
                }
            })
            .state('app.my_bodies.edit_circles', {
                url: '/my_bodies/circle',
                data: {'pageTitle': 'Edit Circles'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/my_bodies/edit_circles.html',
                        controller: 'EditCirclesController as vm'
                    }
                }
            });
    }

    function CircleDirective() {
        return {
            restrict: 'E',
            scope: {
                circle: '='
            },
            templateUrl: 'modules/rewrite/my_bodies/directive_circle.html'
        };
    }

    function MyBodiesController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;

        // TODO replace with real http requests
        vm.bodies = [
            {
                title: "AEGEE-Dresden",
                subtitle: "The best local ever"
            }, {
                title: "ITC",
                subtitle: "Nerds and stuff"
            }
        ];
    }

    function EditCirclesController() {
        var vm = this;

        vm.body = {
            title: "AEGEE-Dresden",
            subtitle: "The best local ever",
            circles: [
                {
                    name: "Members"
                },
                {
                    name: "Board"
                }
            ]
        };

        vm.users = [
            {
                name: "Nico",
                circles: ["Members", "Board"]
            },
            {
                name: "Dresdino",
                circles: ["Members"]
            }
        ];

        var intersectCircles = function(users, circles) {
            var helper = function(usercircles, circles) {
                return circles.map(function(item) {
                    return {
                        name: item.name,
                        checked: (usercircles.findIndex((x) => x == item.name) !== -1)
                    }
                });
            }

            users = users.forEach(function(user, index, users) {
                users[index].circles_fullarray = helper(user.circles, circles);
            });
        }

        intersectCircles(vm.users, vm.body.circles);

    }

})();