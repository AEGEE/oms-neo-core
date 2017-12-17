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

        // Use the get by token endpoint to get the bodies
        vm.getUserBodies = function() {
            $http({
                method: 'POST',
                url: '/api/tokens/user',
                data: {
                    token: localStorage.getItem("X-Auth-Token")
                }
            })
            .then(function successCallback(response) {
                vm.bodies = response.data.data.bodies;
            }).catch(function(err) {showError(err);});
        }
        vm.getUserBodies();
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

        // Intersects each users circles with all available ones to render the table
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