(function ()
{
    'use strict';

    angular
        .module('app.recrutement_campaigns', [])
        .config(config)
        .controller('RecruitementCampaignsController', RecruitementCampaignsController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.recrutement_campaigns', {
                url: '/recrutement_campaigns',
                data: {'pageTitle': 'Recruitement campaigns'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/recrutement_campaigns/recrutement_campaigns.php',
                        controller: 'RecruitementCampaignsController as vm'
                    }
                }
            });
    }

    function RecruitementCampaignsController($http, $q, $compile, $scope) {
        // Data
        var vm = this;
        vm.campaign = {};
        vm.campaign.customFields = [];

        vm.antennae = {};
        vm.filter = {};

        vm.isSearching = false;
        vm.lastSearch = "";

        vm.fieldTypes = [
            {
                id: 1,
                name: 'Text field'
            }, {
                id: 2,
                name: 'Text area'
            }
        ]

        vm.is_superadmin = isSuperAdmin;

        var grid_container = "#gridContainer";
        var grid_pager = "#gridPager";

        // Methods
        vm.loadGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getRecruitementCampaigns";
            params.datatype = "json";
            params.mtype = 'GET';
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.colNames = [
                'Actions',
                'Body',
                'Start date',
                'End date',
                'Link',
                'Custom fields'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    sortable: false,
                    hidden: true,
                    width: 50
                }, {
                    name: 'name',
                    index: 'name',
                    hidden: !vm.is_superadmin,
                    width: 200
                }, {
                    name: 'start_date',
                    index: 'start_date',
                    width: 100
                }, {
                    name: 'end_date',
                    index: 'end_date',
                    width: 100
                }, {
                    name: 'link',
                    index: 'link',
                    sortable: false,
                    width: 150
                }, {
                    name: 'custom_fields',
                    index: 'custom_fields',
                    sortable: false,
                    width: 150
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = grid_pager;
            params.sortname = 'start_date';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Campaigns";

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
            object.start_date = $('#fStart').val();
            object.end_date = $('#fEnd').val();

            object.is_grid = 1;
            jQuery(grid_container).jqGrid('setPostData', object);
            jQuery(grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            $('#fStart').val("");
            $('#fEnd').val("");
            vm.searchGrid();
        }

        vm.getBodies = function() {
            $http({
                method: 'GET',
                url: '/api/getBodies',
                params: {
                    limit: 100000
                }
            })
            .then(function successCallback(response) {
                vm.antennae = response.data;
            });
        }

        vm.closeAndReset = function() {
            vm.campaign = {};
            vm.campaign.customFields = [];

            $('#start_date').val("");
            $('#end_date').val("");

            $('#campaignModal').modal('hide');
        }

        vm.addCustomField = function() {
            var tmp = {
                name: '',
                type: ''
            }
            vm.campaign.customFields.push(tmp);
        }

        vm.removeLastField = function() {
            vm.campaign.customFields.pop();
        }

        vm.addDatePickers = function() {
            $('.datePicker').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }

        vm.checkLinkAvailability = function() {
            vm.campaign.link = vm.campaign.link.toLowerCase().replace(/\s+/g,'');

            if(vm.isSearching) {
                setTimeout(vm.checkLinkAvailability, 1000);
                return;
            }

            if(vm.lastSearch == vm.campaign.link) {
                return;
            }

            vm.lastSearch = vm.campaign.link;
            vm.isSearching = true;
            $('#isTaken').hide();
            $('#isAvailable').hide();
            $('#searching').show();

            var deferred = $q.defer();
            $http({
                method: "GET",
                url: '/api/checkLinkAvailability',
                params: {
                    link: vm.campaign.link
                }
            })
            .success(function successCallback(response) {
                deferred.resolve(response);
            });
            deferred.promise.then(function (result) {
                $('#searching').hide();
                $('#isTaken').hide();
                $('#isAvailable').hide();

                if(result.exists == 0) {
                    $('#isAvailable').show();
                } else {
                    $('#isTaken').show();
                }
                vm.isSearching = false;
            });
            
        }

        vm.saveCampaign = function() {
            vm.campaign.start_date = $('#start_date').val();
            vm.campaign.end_date = $('#end_date').val();
            $http({
                method: "POST",
                url: '/api/saveCampaign',
                data: vm.campaign
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Campaign saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchGrid();
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

        vm.toggleFilters = function() {
            if($('#filters').is(':visible')) {
                $('#filters').hide('slow');
            } else {
                $('#filters').show('slow');
            }
        }

        ///////
        vm.loadGrid();
        vm.getBodies();
        vm.addDatePickers();
    }

})();