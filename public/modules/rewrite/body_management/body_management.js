(function ()
{
    'use strict';

    angular
        .module('app.body_management', [])
        .config(config)
        .controller('BodyController', BodyController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.body_management', {
                url: '/body_management',
                data: {'pageTitle': 'Body Management'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/rewrite/body_management/list.html',
                        controller: 'BodyController as vm'
                    }
                }
            });
    }

    function BodyController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
    }

})();