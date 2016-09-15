(function ()
{
    'use strict';

    angular
        .module('app.recruted_users', [])
        .config(config)
        .controller('RecrutedUsersController', RecrutedUsersController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.recruted_users', {
                url: '/recruted_users',
                data: {'pageTitle': 'Recruted users'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/recruted_users/recruted_users.php',
                        controller: 'RecrutedUsersController as vm'
                    }
                }
            });
    }

    function RecrutedUsersController($http) {
        // Data
        var vm = this;

        // Methods
        
        ///////
    }

})();