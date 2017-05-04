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
    }

})();