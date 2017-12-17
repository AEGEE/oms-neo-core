(function ()
{
    'use strict';

    angular
        .module('app.users', [])
        .config(config)
        .directive('userpreview', UserPreviewDirective)
        .directive('omsSimpleUser', SimpleUserDirective)
        .controller('UserController', UserController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.users', {
                url: '/users',
                data: {'pageTitle': 'Users'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/users/users.html',
                        controller: 'UserController as vm'
                    }
                }
            });
    }

    function UserPreviewDirective() {
        return {
            restrict: 'E',
            scope: {
                user: '='
            },
            templateUrl: 'modules/loggedIn/users/directive_userpreview.html'
        };
    }

    function SimpleUserDirective($http) {

        function link(scope, elements, attrs) {
            scope.message = "Fetching user";
            attrs.$observe('userid', function(value) {
                if(!value)
                    return;
                $http({
                    url: '/api/users/' + value,
                    method: 'GET'
                }).then(function(response) {
                    scope.fetched_user=response.data.data;
                    scope.message = "";
                }).catch(function(error) {
                    scope.message="Could not fetch"
                });
            })
        }

        return {
            templateUrl: 'modules/loggedIn/users/directive_simple_user.html',
            restrict: 'E',
            scope: {
                userid: '@'
            },
            link: link,
        };
    }


    function UserController($http, $compile, $scope, $state) {
        // Data
        var vm = this;
        vm.query = "";


        vm.injectParams = (params) => {
            params.name = vm.query
            return params;
        }
        infiniteScroll($http, vm, '/api/users', vm.injectParams);
    }

})();