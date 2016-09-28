(function ()
{
    'use strict';

    angular
        .module('app.fees_management', [])
        .config(config)
        .controller('FeesManagementController', FeesManagementController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.fees_management', {
                url: '/fees_management',
                data: {'pageTitle': 'Fees management'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/fees_management/fees_management.php',
                        controller: 'FeesManagementController as vm'
                    }
                }
            });
    }

    function FeesManagementController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
        vm.fee = {};
        vm.filter = {};

        vm.availability_units = [
            {
                id: 1,
                name: 'Month'
            },
            {
                id: 2,
                name: 'Year'
            }
        ];

        var fees_grid_container = "#feesGrid";
        var fees_grid_pager = "#feesPager";

        // Methods

        vm.loadFeesGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getFees";
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
                'Availability',
                'Availability unit',
                'Price (Currency)',
                'Mandatory for all members'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    hidden: moduleAccess.fees_management == 0,
                    sortable: false,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    width: 200
                }, {
                    name: 'availability',
                    index: 'availability',
                    width: 100
                }, {
                    name: 'availability_unit',
                    index: 'availability_unit',
                    width: 100
                }, {
                    name: 'price',
                    index: 'price',
                    width: 100
                }, {
                    name: 'is_mandatory',
                    index: 'is_mandatory',
                    sortable: false,
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = fees_grid_pager;
            params.sortname = 'title';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Fees";

            params.gridComplete = function() {
                $compile($('.clickMeFee'))($scope);
            }

            jQuery(fees_grid_container).jqGrid(params);

            jQuery(fees_grid_container).jqGrid('navGrid', fees_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchFeeGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            jQuery(fees_grid_container).jqGrid('setPostData', object);
            jQuery(fees_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchFeeGrid();
        }

        vm.closeAndReset = function() {
            $('#feeModal').modal('hide');
            vm.fee = {};
        }

        vm.saveFee = function() {
            $http({
                method: "POST",
                url: '/api/saveFee',
                data: vm.fee,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Fee saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchFeeGrid();
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

        vm.editFee = function(id) {
            $http({
                method: 'GET',
                url: "/api/getFee",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.fee = response.data.fee;
                    if(vm.fee.is_mandatory == 1) { // Checkbox fix
                        vm.fee.is_mandatory = true;
                    }
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
            $('#feeModal').modal('show');
        }

        vm.exportGrid = function() {
            $http({
                url: 'api/getFees',
                method: 'GET',
                responseType: 'arraybuffer',
                params: {
                    name: vm.filter.name,
                    availability_from: vm.filter.availability_from,
                    availability_to: vm.filter.availability_to,
                    availability_unit: vm.filter.availability_unit,
                    price_from: vm.filter.price_from,
                    price_to: vm.filter.price_to,
                    currency: vm.filter.currency,
                    mandatory: vm.filter.mandatory,
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
        vm.loadFeesGrid();
    }

})();