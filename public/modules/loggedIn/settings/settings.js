(function ()
{
    'use strict';

    angular
        .module('app.settings', [])
        .config(config)
        .controller('SettingController', SettingController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.settings', {
                url: '/settings',
                data: {'pageTitle': 'Settings'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/settings/settings.html',
                        controller: 'SettingController as vm'
                    }
                }
            });
    }

    function SettingController($http, $compile, $scope, $window) {
        // Data
        var vm = this;
        vm.option = {};
        vm.email = {};

        vm.filter = {};
        vm.filterEmail = {};

        var settings_grid_container = "#settingGrid";
        var settings_grid_pager = "#settingPager";

        var emails_grid_container = "#emailGrid";
        var emails_grid_pager = "#emailPager";

        var emailsGridLoaded = false;

        // Methods

        vm.loadSettingsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1,
                not_editable: 0
            };

            params.url = "/api/getOptions";
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
                'Value',
                'Description'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    sortable: false,
                    width: 25
                }, {
                    name: 'name',
                    index: 'name',
                    width: 100
                }, {
                    name: 'value',
                    index: 'value',
                    sortable: false,
                    width: 50
                }, {
                    name: 'description',
                    index: 'description',
                    sortable: false,
                    width: 200
                }
            ];
            params.rowNum = 1000;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = settings_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Settings";

            params.gridComplete = function() {
                $compile($('.clickMeSett'))($scope);
            }

            jQuery(settings_grid_container).jqGrid(params);

            jQuery(settings_grid_container).jqGrid('navGrid', settings_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchOptionsGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            object.not_editable = 0;
            jQuery(settings_grid_container).jqGrid('setPostData', object);
            jQuery(settings_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchOptionsGrid();
        }

        vm.closeAndReset = function() {
            $('#optionModal').modal('hide');
            vm.option = {};
        }

        vm.saveOption = function() {
            $http({
                method: "POST",
                url: '/api/saveOption',
                data: vm.option,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Option saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchOptionsGrid();
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

        vm.editOption = function(id) {
            $http({
                method: 'GET',
                url: "/api/getOption",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.option = response.data.option;
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
            $('#optionModal').modal('show');
        }

        // Emails..
        vm.loadEmailsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1,
                not_editable: 0
            };

            params.url = "/api/getEmailTemplates";
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
                'Email title',
                'Description'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    sortable: false,
                    width: 25
                }, {
                    name: 'name',
                    index: 'name',
                    width: 100
                }, {
                    name: 'title',
                    index: 'title',
                    width: 100
                }, {
                    name: 'description',
                    index: 'description',
                    sortable: false,
                    width: 200
                }
            ];
            params.rowNum = 1000;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = emails_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Email templates";

            params.gridComplete = function() {
                $compile($('.clickMeTpl'))($scope);
            }

            jQuery(emails_grid_container).jqGrid(params);

            jQuery(emails_grid_container).jqGrid('navGrid', emails_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.closeAndResetEmail = function() {
            $('#emailModal').modal('hide');
            tinymce.get('emailContent').remove();
            vm.option = {};
        }

        vm.searchEmailsGrid = function() {
            var object = {};
            object = vm.filterEmail;

            object.is_grid = 1;
            jQuery(emails_grid_container).jqGrid('setPostData', object);
            jQuery(emails_grid_container).trigger("reloadGrid");
        }

        vm.clearEmailSearch = function() {
            vm.filterEmail = {};
            vm.searchEmailsGrid();
        }

        vm.saveEmail = function() {
            vm.email.content = tinymce.get("emailContent").getContent();
            $http({
                method: "POST",
                url: '/api/saveEmailTemplate',
                data: vm.email,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Email saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndResetEmail();
                    vm.searchEmailsGrid();
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

        vm.editEmail = function(id) {
            $http({
                method: 'GET',
                url: "/api/getEmailTemplate",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.email = response.data.email;
                    $('#emailContent').val(response.data.email.content);
                    vm.openEmailModal();
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

        vm.openEmailModal = function() {
            tinymce.init({
                selector: '#emailContent',
                menubar: false
            });
            $('#emailModal').modal('show');
        }

        vm.viewEmail = function(templateCode) {
            $window.open('/previewEmail/'+templateCode, '_blank');
        }

        ///////
        vm.loadSettingsGrid();

        $('#emailTab').click(function() {
            if(!emailsGridLoaded) {
                setTimeout(vm.loadEmailsGrid, 200); // Fix, otherwise grid won't take the the full width of the container..
                emailsGridLoaded = true;
            } else {
                vm.searchEmailsGrid();
            }
        });

    }

})();