(function ()
{
    'use strict';

    angular
        .module('app.my_bodies', [])
        .config(config)
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

    function MyBodiesController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
    }

})();