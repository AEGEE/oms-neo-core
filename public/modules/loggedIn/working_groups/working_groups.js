(function ()
{
    'use strict';

    angular
        .module('app.working_groups', [])
        .config(config)
        .controller('WorkGroupController', WorkGroupController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.working_groups', {
                url: '/working_groups',
                data: {'pageTitle': 'Working groups'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/working_groups/working_groups.php',
                        controller: 'WorkGroupController as vm'
                    }
                }
            });
    }

    function WorkGroupController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
        vm.workGroup = {};
        vm.filter = {};

        var wg_grid_container = "#workGroupGrid";
        var wg_grid_pager = "#workGroupPager";

        // Methods

        vm.loadWorkingGroupsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getWorkingGroups";
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
                'Description'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    hidden: moduleAccess.working_groups == 0,
                    sortable: false,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    width: 200
                }, {
                    name: 'description',
                    index: 'description',
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = wg_grid_pager;
            params.sortname = 'title';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Working groups";

            params.gridComplete = function() {
                $compile($('.clickMeWg'))($scope);
            }

            jQuery(wg_grid_container).jqGrid(params);

            jQuery(wg_grid_container).jqGrid('navGrid', wg_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchWorkGroupGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(wg_grid_container).jqGrid('setPostData', object);
            jQuery(wg_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchWorkGroupGrid();
        }

        vm.closeAndReset = function() {
            $('#workGroupModal').modal('hide');
            vm.workGroup = {};
        }

        vm.saveWorkGroup = function() {
            $http({
                method: "POST",
                url: '/api/saveWorkGroup',
                data: vm.workGroup,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'WorkGroup saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchWorkGroupGrid();
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

        vm.editWorkGroup = function(id) {
            $http({
                method: 'GET',
                url: "/api/getWorkGroup",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.workGroup = response.data.workgroup;
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
            $('#workGroupModal').modal('show');
        }

        vm.exportGrid = function() {
            $http({
                url: 'api/getWorkingGroups',
                method: 'GET',
                responseType: 'arraybuffer',
                params: {
                    name: vm.filter.name,
                    export: 1
                },
                headers: {
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            }).success(function(data){
                var blob = new Blob([data], {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"});
                var objectUrl = URL.createObjectURL(blob);
                window.open(objectUrl);
                $.gritter.add({
                    title: 'Export generated successfully!',
                    text: 'If you did not receive it, please make sure that your browser isn\'t blocking pop-ups.',
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
            });
        }

        vm.toggleFilters = function() {
            if($('#filters').is(':visible')) {
                $('#filters').hide('slow');
            } else {
                $('#filters').show('slow');
            }
        }

        ///////
        vm.loadWorkingGroupsGrid();
    }

})();