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

    function RecrutedUsersController($http, $compile, $scope) {
        // Data
        var vm = this;
        vm.filter = {};

        vm.antennae = {};
        vm.campaigns = {};

        vm.is_superadmin = isSuperAdmin;

        vm.statuses = [
            {
                id: 0,
                name: 'Opened'
            }, {
                id: -1,
                name: 'Rejected'
            }, {
                id: 1,
                name: 'In progress'
            }, {
                id: 2,
                name: 'Accepted'
            }
        ];

        var grid_container = "#gridContainer";
        var grid_pager = "#gridPager";
        // Methods
        
        vm.loadGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getRecrutedUsers";
            params.datatype = "json";
            params.mtype = 'GET';
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.colNames = [
                'Actions',
                'Full name',
                'Date of birth',
                'Registration email',
                'Gender',
                'Antenna',
                'Status'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    sortable: false,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    width: 200
                }, {
                    name: 'date_of_birth',
                    index: 'date_of_birth',
                    width: 100
                }, {
                    name: 'contact_email',
                    index: 'contact_email',
                    width: 100
                }, {
                    name: 'gender',
                    index: 'gender',
                    width: 100
                }, {
                    name: 'antenna',
                    index: 'antenna',
                    sortable: false,
                    hidden: !vm.is_superadmin,
                    width: 100
                }, {
                    name: 'status',
                    index: 'status',
                    sortable: false,
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = grid_pager;
            params.sortname = 'start_date';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Users";

            params.gridComplete = function() {
                $compile($('.clickMe'))($scope);
            }

            jQuery(grid_container).jqGrid(params);

            jQuery(grid_container).jqGrid('navGrid', grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(grid_container).jqGrid('setPostData', object);
            jQuery(grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchGrid();
        }

        vm.getAntennae = function() {
            $http({
                method: 'GET',
                url: '/api/getAntennae',
                params: {
                    limit: 100000
                }
            })
            .then(function successCallback(response) {
                vm.antennae = response.data;
            }, function errorCallback(response) {
                $.gritter.add({
                    title: 'Error!',
                    text: response.data,
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
            });
        }

        vm.getCampaigns = function() {
            $http({
                method: 'GET',
                url: '/api/getRecrutementCampaigns',
                params: {
                    limit: 100000
                }
            })
            .then(function successCallback(response) {
                vm.campaigns = response.data;
            }, function errorCallback(response) {
                $.gritter.add({
                    title: 'Error!',
                    text: response.data,
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
            });
        }

        ///////
        vm.loadGrid();
        vm.getAntennae();
        vm.getCampaigns();
    }

})();