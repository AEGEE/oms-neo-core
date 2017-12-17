(function ()
{
    'use strict';

    angular
        .module('app.modules', [])
        .config(config)
        .controller('ModuleController', ModuleController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.modules', {
                url: '/moduleManagement',
                data: {'pageTitle': 'Modules'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/modules/modules.php',
                        controller: 'ModuleController as vm'
                    }
                }
            });
    }

    function ModuleController($http, $compile, $scope) {
        // Data
        var vm = this;
        vm.filter = {};

        var module_grid_container = "#moduleGrid";
        var module_grid_pager = "#modulePager";

        // Methods

        vm.loadModulesGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/modules";
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
                'Base url',
                'Active'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    hidden: false,
                    sortable: false,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    width: 150
                }, {
                    name: 'base_url',
                    index: 'base_url',
                    width: 150
                }, {
                    name: 'is_active',
                    index: 'is_active',
                    sortable: false,
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = module_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Modules";
            params.subGrid = true;

            params.gridComplete = function() {
                $compile($('.clickMeModule'))($scope);
            }

            params.subGridRowExpanded = function(subgridDivID, rowID) {
                vm.onModuleSubGridRowExpanded(subgridDivID, rowID);
            };

            jQuery(module_grid_container).jqGrid(params);

            jQuery(module_grid_container).jqGrid('navGrid', module_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.onModuleSubGridRowExpanded = function(subgridDivID, rowID) {
            vm.addPageSubgrid(subgridDivID, rowID);
        }

        vm.addPageSubgrid = function (subgridDivID, rowID) {
            var subgridTableID = subgridDivID + "_item";
            jQuery("#" + subgridDivID).html('');
            jQuery("#" + subgridDivID).attr('style', 'margin: 4px;');
            jQuery("#" + subgridDivID).append("<div class='row' style='margin-right: 0px'><div class='col-md-12'><center><table id='" + subgridTableID + "'></table></center></div></div>");
            jQuery("#" + subgridTableID).jqGrid({
                url: '/api/subrid/modules',
                caption: "Module pages",
                datatype: "json",
                mtype: 'GET',
                autowidth: true,
                height: 'auto',
                rownumbers: true,
                shrinkToFit: true,
                styleUI: 'Bootstrap',
                postData: {
                    id: rowID,
                    is_grid: 1,
                    rows: 10000,
                },
                colNames: [
                    'Actions',
                    'Name',
                    'Internal Code',
                    'Active',
                    'Module'
                ],
                colModel: [
                    {
                        name: 'actions',
                        index: 'actions',
                        hidden: false,
                        sortable: false,
                        width: 25
                    }, {
                        name: 'name',
                        index: 'name',
                        width: 200
                    }, {
                        name: 'code',
                        index: 'code',
                        hidden: true,
                        width: 100
                    }, {
                        name: 'is_active',
                        index: 'is_active',
                        width: 50
                    }, {
                        name: 'module_name',
                        index: 'module_name',
                        sortable: false,
                        hidden: true,
                        width: 200
                    }
                ],
                gridComplete: function() {
                    $compile($('.clickMeModulePage'))($scope);
                },
                viewrecords: true,
                rowNum: 10000
            });
        }

        vm.searchModulesGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(module_grid_container).jqGrid('setPostData', object);
            jQuery(module_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchModulesGrid();
        }

        vm.activateDeactivatePage = function(id, what) {
            if(!confirm("Are you sure you want to "+what+" this page?")) {
                return;
            }
            $http({
                method: "POST",
                url: '/api/page/' + id + '/activate',
                data: {
                    id: id
                },
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Status changed successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.searchModulesGrid();
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

        vm.activateDeactivateModule = function(id, what) {
            if(!confirm("Are you sure you want to "+what+" this module?")) {
                return;
            }
            $http({
                method: "POST",
                url: '/api/module/' + id + '/activate',
                data: {
                    id: id
                },
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Status changed successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.searchModulesGrid();
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

        vm.getSharedSecret = function() {
            $http({
                method: 'GET',
                url: "/api/secret/shared"
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    $('#sharedSecret').html(response.data.key);
                    $('#keyContainer').show();
                    $('#revealKeyBtn').hide();
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

        vm.generateNewSharedSecret = function() {
            $http({
                method: 'POST',
                url: "/api/secret/shared"
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    $('#sharedSecret').html(response.data.key);
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

        vm.toggleFilters = function() {
            if($('#filters').is(':visible')) {
                $('#filters').hide('slow');
            } else {
                $('#filters').show('slow');
            }
        }

        ///////
        vm.loadModulesGrid();
    }

})();
