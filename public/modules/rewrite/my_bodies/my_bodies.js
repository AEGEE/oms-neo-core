(function ()
{
    'use strict';

    angular
        .module('app.my_bodies', [])
        .config(config)
        .directive('circle', CircleDirective)
        .controller('MyBodiesController', MyBodiesController);

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
            });
    }

    function TileDirective() {
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

})();