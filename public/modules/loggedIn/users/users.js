(function ()
{
    'use strict';

    angular
        .module('app.users', [])
        .config(config)
        .controller('UserController', UserController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.users', {
                url: '/users',
                data: {'pageTitle': 'Users'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/users/users.html',
                        controller: 'UserController as vm'
                    }
                }
            });
    }

    function UserController($http, $compile, $scope, $state) {
        // Data
        var vm = this;
        vm.user = {};
        vm.filter = {};
        vm.roles = {};
        vm.fees = {};

        vm.genderTypes = [
            {
                id: 1,
                name: 'Male'
            }, {
                id: 2,
                name: 'Female'
            }, {
                id: 3,
                name: 'Other'
            }
        ];

        vm.statuses = [
            {
                id: 1,
                name: 'Active'
            }, {
                id: 2,
                name: 'Inactive'
            }, {
                id: 3,
                name: 'Suspended'
            }
        ];

        vm.registrationFields = {};
        vm.departments = {};

        var user_grid_container = "#usersGrid";
        var user_grid_pager = "#usersPager";

        // Methods


        vm.getRegistrationFields = function() {
            $http({
                method: 'GET',
                url: '/api/getRegistrationFields'
            })
            .then(function successCallback(response) {
                vm.registrationFields = response.data;
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

        vm.getDepartments = function() {
            $http({
                method: 'GET',
                url: '/api/getDepartments'
            })
            .then(function successCallback(response) {
                vm.departments = response.data;
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

        vm.loadUsersGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getUsers";
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
                'Department',
                'Internal email',
                'Studies type',
                'Studies field',
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
                    width: 100
                }, {
                    name: 'department',
                    index: 'department',
                    sortable: false,
                    width: 100
                }, {
                    name: 'internal_email',
                    index: 'internal_email',
                    width: 100
                }, {
                    name: 'studies_type',
                    index: 'studies_type',
                    sortable: false,
                    width: 100
                }, {
                    name: 'studies_field',
                    index: 'studies_field',
                    sortable: false,
                    width: 95
                }, {
                    name: 'status',
                    index: 'status',
                    sortable: false,
                    hidden: true,
                    width: 1
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = user_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Users";

            params.gridComplete = function() {
                $compile($('.clickMeUser'))($scope);
            }

            params.rowattr = function (rd) {
                var row = rd.status;
                switch(row) {
                    case '1': // Active
                        return {'style': "background: #5cb85c; color: #000"}; // green
                    case '2': // Inactive
                        return {'style': "background: #C1BCBC; color: #000"}; // grey
                    case '3': // Suspended
                        return {'style': "background: #d9534f; color: #000"}; // red
                }
            }

            jQuery(user_grid_container).jqGrid(params);

            jQuery(user_grid_container).jqGrid('navGrid', user_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchUserGrid = function() {
            var object = {};
            vm.filter.antenna_id = $('#fAntenna').val();
            vm.filter.department_id = $('#fDepartment').val();
            vm.filter.studies_type_id = $('#fStudyType').val();
            vm.filter.studies_field_id = $('#fStudyField').val();
            vm.filter.date_of_birth = $('#fDob').val();

            object = vm.filter;

            object.is_grid = 1;
            jQuery(user_grid_container).jqGrid('setPostData', object);
            jQuery(user_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            $('#fAntenna').val("").trigger("change");
            $('#fStudyType').val("").trigger("change");
            $('#fStudyField').val("").trigger("change");
            $('#fDepartment').val("").trigger("change");
            $('#fDob').val("");
            vm.searchUserGrid();
        }

        vm.closeAndReset = function() {
            $('#userModal').modal('hide');
            vm.user = {};
            $('#antenna').val("").trigger("change");
            $('#studies_type').val("").trigger("change");
            $('#study_field').val("").trigger("change");
            $('#date_of_birth').val("");
        }

        vm.closeAndResetActivate = function() {
            $('#activateUserModal').modal('hide');
            vm.user = {};
            $('#activateDepartment').val("").trigger("change");
            $('.paidOnDateFee').val("");

        }

        vm.saveUser = function() {
            vm.user.antenna_id = $('#antenna').val();
            vm.user.studies_type = $('#studies_type').val();
            vm.user.study_field = $('#study_field').val();
            vm.user.date_of_birth = $('#date_of_birth').val();

            $http({
                method: "POST",
                url: '/api/signup',
                data: vm.user,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'User saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchUserGrid();
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

        vm.editUser = function(id, modal_id) {
            $http({
                method: 'GET',
                url: "/api/getUser",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.user = response.data.user;
                    $('#antenna').val(response.data.user.antenna_id).trigger("change");
                    $('#studies_type').val(response.data.user.studies_type_id).trigger("change");
                    $('#study_field').val(response.data.user.studies_field_id).trigger("change");
                    $('#date_of_birth').val(response.data.user.date_of_birth);
                    $('#gender').val(response.data.user.gender).trigger("change");
                    $('#'+modal_id).modal('show');
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

        vm.exportGrid = function() {
            $http({
                url: 'api/getUsers',
                method: 'GET',
                responseType: 'arraybuffer',
                params: {
                    name: vm.filter.name,
                    date_of_birth: $('#fDob').val(),
                    contact_email: vm.filter.contact_email,
                    gender: vm.filter.gender,
                    antenna_id: $('#fAntenna').val(),
                    department_id: $('#fDepartment').val(),
                    internal_email: vm.filter.internal_email,
                    studies_type_id: $('#fStudyType').val(),
                    studies_field_id: $('#fStudyField').val(),
                    status: vm.filter.status,
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

        vm.initControls = function() {
            $('#fDob, #date_of_birth, .paidOnDateFee').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $("#fAntenna, #fStudyType, #fStudyField, #fDepartment, #antenna, #studies_type, #study_field, #activateDepartment").select2({width: '100%'});
        }

        vm.getRoles = function() {
            $http({
                method: 'GET',
                url: "/api/getRoles"
            }).then(function successCallback(response) {
                vm.roles = response.data.rows;
            })
        }

        vm.addContoltoFee = function() {
            $('.paidOnDateFee').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }

        vm.getFees = function() {
            $http({
                method: 'GET',
                url: "/api/getFees"
            }).then(function successCallback(response) {
                vm.fees = response.data.rows;
                setTimeout(vm.addContoltoFee, 1000);
            })
        }

        vm.activateUser = function() {
            vm.user.department_id = $('#activateDepartment').val();
            vm.user.feesPaid = {};
            $.each(vm.fees, function(key, val) {   
                vm.user.feesPaid[val.cell[0]] = $('#feepaid_'+val.cell[0]).val();
            });
            $http({
                method: "POST",
                url: '/api/activateUser',
                data: vm.user,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'User activated successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndResetActivate();
                    vm.searchUserGrid();
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

        vm.visitProfile = function(url) {
            $state.go('app.profile', {
                    'seo': url
            });
        }

        ///////
        vm.loadUsersGrid();
        vm.getRegistrationFields();
        vm.getDepartments();
        vm.getRoles();
        vm.getFees();
        vm.initControls();
    }

})();