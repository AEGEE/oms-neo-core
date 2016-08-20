(function ()
{
    'use strict';

    angular
        .module('app.antennae_management', [])
        .config(config)
        .controller('AntennaController', AntennaController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.antennae_management', {
                url: '/antennae_management',
                data: {'pageTitle': 'Antennae Management'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/antennae_management/antennae_management.html',
                        controller: 'AntennaController as vm'
                    }
                }
            });
    }

    function AntennaController($http, $compile, $scope, $window, $httpParamSerializer) {
        // Data
        var vm = this;
        vm.antenna = {};
        vm.filter = {};

        vm.countries = countries;

        var ant_grid_container = "#antennaeGrid";
        var ant_grid_pager = "#antennaePager";

        // Methods

        vm.loadAntennaeGrid = function() {
            var params = {};
            params.postData = {}

            params.url = "/api/getAntennaeForGrid";
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
                'City',
                'Country'
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
                    name: 'city',
                    index: 'city',
                    width: 100
                }, {
                    name: 'country',
                    index: 'country',
                    sortable: false,
                    width: 150
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = ant_grid_pager;
            params.sortname = 'title';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Antennae";

            params.gridComplete = function() {
                $compile($('.clickMeAnt'))($scope);
            }

            jQuery(ant_grid_container).jqGrid(params);

            jQuery(ant_grid_container).jqGrid('navGrid', ant_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchAntennaGrid = function() {
            var object = {};
            object = vm.filter;
            object.country_id = $('#fCountry').val();

            jQuery(ant_grid_container).jqGrid('setPostData', object);
            jQuery(ant_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            $('#fCountry').val("").trigger("change");
            vm.searchAntennaGrid();
        }

        vm.closeAndReset = function() {
            $('#antennaModal').modal('hide');
            vm.antenna = {};
            $('#countries').val("").trigger("change");
        }

        vm.saveAntenna = function() {
            $http({
                method: "POST",
                url: '/api/saveAntenna',
                data: {
                    id: vm.antenna.id,
                    name: vm.antenna.name,
                    city: vm.antenna.city,
                    country_id: $('#countries').val()
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Antenna saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchAntennaGrid();
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

        vm.editAntenna = function(id) {
            $http({
                method: 'GET',
                url: "/api/getAntenna",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.antenna = response.data.antenna;
                    $('#countries').val(response.data.antenna.country_id).trigger("change");
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
            $('#antennaModal').modal('show');
        }

        vm.exportGrid = function() {
            $http({
                url: 'api/getAntennaeForGrid',
                method: 'GET',
                responseType: 'arraybuffer',
                params: {
                    name: vm.filter.name,
                    city: vm.filter.city,
                    country_id: $('#fCountry').val(),
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
        vm.loadAntennaeGrid();
        $("#countries").select2({width: '100%'});
        $("#fCountry").select2({width: '100%'});
        
    }

})();