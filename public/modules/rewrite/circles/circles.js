(function ()
{
  'use strict';

  angular
  .module('app.circles', [])
  .config(config)
  .directive('simplecircle', SimpleCircleDirective)
  .controller('CirclesController', CirclesController)
  .controller('SingleCircleController', SingleCircleController);

  /** @ngInject */
  function config($stateProvider)
  {
    // State
    $stateProvider
    .state('app.circles', {
      url: '/circles',
      data: {'pageTitle': 'Circles'},
      views   : {
        'pageContent@app': {
          templateUrl: 'modules/rewrite/circles/circles.html',
          controller: 'CirclesController as vm'
        }
      }
    })
    .state('app.circles.single', {
      url: '/:id',
      data: {'pageTitle': 'Circle'},
      views   : {
        'pageContent@app': {
          templateUrl: 'modules/rewrite/circles/single_circle.html',
          controller: 'SingleCircleController as vm'
        }
      }
    });
  }

  function SimpleCircleDirective() {
    return {
      restrict: 'E',
      scope: {
        circle: '='
      },
      templateUrl: 'modules/rewrite/circles/directive_circle_simple.html'
    };
  }

  function CirclesController($http) {
    // Data
    var vm = this;
    vm.query = "";
    vm.recursive = true;


    vm.injectParams = (params) => {
      params.name = vm.query
      params.recursive = vm.recursive;
      return params;
    }
    infiniteScroll($http, vm, '/api/circles', vm.injectParams);
  }

  function SingleCircleController($http, $stateParams) {
    var vm = this;

    vm.loadCircle = () => {
      $http({
        url: '/api/circles/' + $stateParams.id,
        method: 'GET'
      }).then((response) => {
        vm.circle = response.data.data;
      }).catch((error) => {
        showError(error);
      });
    }

    vm.injectParams = (params) => {
      params.name = vm.query;
      params.recursive = vm.recursive; 
      return params;
    }


    infiniteScroll($http, vm, '/api/circles/' + $stateParams.id + '/members', vm.injectParams);
    vm.loadCircle();
  } 

})();