(function ()
{
    'use strict';

    angular
        .module('app.members', [])
        .config(config)
        .controller('MemberController', MemberController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.members', {
                url: '/members',
                data: {'pageTitle': 'Members'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/members/members.php',
                        controller: 'MemberController as vm'
                    }
                }
            });
    }

    function MemberController($http, $compile, $scope, $state) {
        // Data
        var vm = this;
        vm.user = {};
        vm.filter = {};
        vm.roles = {};
        vm.fees = {};
        vm.members = {};

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
            });
        }

        vm.getDepartments = function() {
            $http({
                method: 'GET',
                url: '/api/getDepartments'
            })
            .then(function successCallback(response) {
                vm.departments = response.data;
            });
        }

        vm.loadUsersGrid = function() {
            $http({
                method: 'GET',
                url: '/api/getMembers'
            })
            .then(function successCallback(response) {
              vm.members = vm.transformMembersData(response);
              vm.showMembersGrid();
            });
        }

        vm.transformMembersData = function(membersData) {
          var rows = [];
          var i = 0;
          for (i = 0; i < membersData.data.length; i++) {
            var memberData = vm.transformMemberData(membersData.data[i]);
            rows.push(memberData);
          }
          return rows;
        }

        vm.transformMemberData = function(member) {

          member.actions = "<button class='btn btn-default btn-xs clickMeUser' title='View user profile' ng-click='vm.visitProfile(\"" + member.seo_url + "\")'><i class='fa fa-search'></i></button>";
          /*
          PHP code:

          if($userX->status == 2 && $max_permission == 1) {
              $actions .= "<button class='btn btn-default btn-xs clickMeUser' title='Activate user' ng-click='vm.editUser(".$userX->id.", \"activateUserModal\")'><i class='fa fa-check'></i></button>";
          } elseif($userX->status != 2 && $userX->id != $userData->id) {
              $actions .= ;
          }
          */
          member.name =  member.first_name + " " + member.last_name;
          member.status = 1;
          member.gender = member.gender == 1 ? 'male' : 'female';
          return member;
        }

        vm.showMembersGrid = function(users) {

            var params = {};
            params.datatype = "local";
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.multiselect = true
            params.caption = "Manipulating Array Data";
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = user_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Users";

            params.colNames = [
                'Actions',
                'Name',
                'Date of birth',
                'Registration email',
                'Gender',
                'Studies type',
                'Studies field',
                'status',
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
                    name: 'studies_type_id',
                    index: 'studies_type_id',
                    sortable: false,
                    width: 100
                }, {
                    name: 'studies_field_id',
                    index: 'studies_field_id',
                    sortable: false,
                    width: 95
                }, {
                    name: 'status',
                    index: 'status',
                    sortable: false,
                    width: 95
                }
            ];

            params.gridComplete = function() {
                $compile($('.clickMeUser'))($scope);
            };

            params.rowattr = function (rd) {
                var row = rd.status;
                switch(row) {
                    case 1: // Active
                        return {'style': "background: #5cb85c; color: #000"}; // green
                    case 2: // Inactive
                        return {'style': "background: #C1BCBC; color: #000"}; // grey
                    case 3: // Suspended
                        return {'style': "background: #d9534f; color: #000"}; // red
                }
            };

            jQuery(user_grid_container).jqGrid(params);


            var mydata = vm.members;
            for(var i=0;i<=mydata.length;i++)
            	jQuery(user_grid_container).jqGrid('addRowData',i+1,mydata[i]);

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
            vm.filter.antenna_id = $('#fBody').val();
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
            $('#fBody').val("").trigger("change");
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
                url: '/api/createUser',
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
                    antenna_id: $('#fBody').val(),
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
            });
        }

        vm.initControls = function() {
            $('#fDob, #date_of_birth, .paidOnDateFee').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $("#fBody, #fStudyType, #fStudyField, #fDepartment, #antenna, #studies_type, #study_field, #activateDepartment").select2({width: '100%'});
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
            });
        }

        vm.visitProfile = function(url) {
            $state.go('app.profile', {
                    'seo': url
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
        vm.loadUsersGrid();
        vm.getRegistrationFields();
        vm.getDepartments();
        vm.getRoles();
        vm.getFees();
        vm.initControls();
    }

})();
