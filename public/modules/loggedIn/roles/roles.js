(function ()
{
    'use strict';

    angular
        .module('app.roles', [])
        .config(config)
        .controller('RoleController', RoleController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.roles', {
                url: '/roles',
                data: {'pageTitle': 'Roles'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/roles/roles.php',
                        controller: 'RoleController as vm'
                    }
                }
            });
    }

    function RoleController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
        vm.role = {};
        vm.filter = {};

        vm.accessOptions = [
            {
                value: 0,
                name: "Read-only"
            }, {
                value: 1,
                name: "Read / Write"
            }
        ];

        var role_grid_container = "#roleGrid";
        var role_grid_pager = "#rolePager";

        // Methods

        vm.loadRolesGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getRoles";
            params.datatype = "json";
            params.mtype = 'GET';
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.colNames = [
                'Actions',
                'Name',
                'Access to pages',
                'Role type'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    hidden: moduleAccess.roles == 0,
                    sortable: false,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    width: 200
                }, {
                    name: 'pages',
                    index: 'pages',
                    sortable: false,
                    width: 100
                }, {
                    name: 'role_type',
                    index: 'role_type',
                    sortable: false,
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = role_grid_pager;
            params.sortname = 'title';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Roles";

            params.gridComplete = function() {
                $compile($('.clickMeRole'))($scope);
            }

            jQuery(role_grid_container).jqGrid(params);

            jQuery(role_grid_container).jqGrid('navGrid', role_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchRolesGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(role_grid_container).jqGrid('setPostData', object);
            jQuery(role_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchRolesGrid();
        }

        vm.closeAndReset = function() {
            $('#roleModal').modal('hide');
            vm.role = {};
        }

        vm.saveRole = function() {
            $http({
                method: "POST",
                url: '/api/saveRole',
                data: vm.role,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Role saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchRolesGrid();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            });
        }

        vm.editRole = function(id) {
            $http({
                method: 'GET',
                url: "/api/getRole",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.role = response.data.role;
                    vm.openModal();
                } else  {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            })
        }

        vm.openModal = function() {
            $('#roleModal').modal('show');
        }

        vm.getModulePages = function() {
            $http({
                method: 'GET',
                url: "/api/getModulePages",
                params: {
                    active: 1,
                    sord: 'module_name'
                }
            }).then(function successCallback(response) {
                    vm.modules = response.data;
                    console.log(vm.modules);
            })
        }

        vm.toggleFilters = function() {
            if($('#filters').is(':visible')) {
                $('#filters').hide('slow');
            } else {
                $('#filters').show('slow');
            }
        }

        ///////
        vm.loadRolesGrid();
        vm.getModulePages();
    }

})();