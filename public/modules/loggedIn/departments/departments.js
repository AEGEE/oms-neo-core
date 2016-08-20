(function ()
{
    'use strict';

    angular
        .module('app.departments', [])
        .config(config)
        .controller('DepartmentController', DepartmentController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.departments', {
                url: '/departments',
                data: {'pageTitle': 'Departments'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/departments/departments.html',
                        controller: 'DepartmentController as vm'
                    }
                }
            });
    }

    function DepartmentController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
        vm.department = {};
        vm.filter = {};

        var deparment_grid_container = "#departmentGrid";
        var department_grid_pager = "#departmentPager";

        // Methods

        vm.loadDepartmentsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getDepartments";
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
            params.pager = department_grid_pager;
            params.sortname = 'title';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Departments";

            params.gridComplete = function() {
                $compile($('.clickMeDep'))($scope);
            }

            jQuery(deparment_grid_container).jqGrid(params);

            jQuery(deparment_grid_container).jqGrid('navGrid', department_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchDepartmentGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(deparment_grid_container).jqGrid('setPostData', object);
            jQuery(deparment_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchDepartmentGrid();
        }

        vm.closeAndReset = function() {
            $('#departmentModal').modal('hide');
            vm.department = {};
        }

        vm.saveDepartment = function() {
            $http({
                method: "POST",
                url: '/api/saveDepartment',
                data: vm.department,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Department saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchDepartmentGrid();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            },
            function errorCallback(response) {
                var messages = "";
                $.each(response.data, function(key, val) {
                    $.each(val, function(key2, val2) {
                        messages += "\n"+val2;
                    });
                });
                $.gritter.add({
                    title: 'Error!',
                    text: "The following errors occoured:"+messages,
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
            });
        }

        vm.editDepartment = function(id) {
            $http({
                method: 'GET',
                url: "/api/getDepartment",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.department = response.data.department;
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
            $('#departmentModal').modal('show');
        }

        vm.exportGrid = function() {
            $http({
                url: 'api/getDepartments',
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
            }).error(function(){
                $.gritter.add({
                    title: 'Error!',
                    text: 'An error occoured! Please try again!',
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
            });
        }

        ///////
        vm.loadDepartmentsGrid();
    }

})();