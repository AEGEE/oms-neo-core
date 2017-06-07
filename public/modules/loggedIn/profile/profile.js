(function ()
{
    'use strict';

    angular
        .module('app.profile', [])
        .config(config)
        .controller('ProfileController', ProfileController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.profile', {
                url: '/profile/{seo}',
                data: {'pageTitle': 'Profile'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/profile/profile.php',
                        controller: 'ProfileController as vm'
                    }
                }
            });
    }

    function ProfileController($http, $stateParams, $state, $scope, $sce, FileUploader) {
        // Data
        var vm = this;
        vm.member = {};
        vm.workingGroups = {};
        vm.board_positions = {};
        vm.roles = {};
        vm.fees_paid = {};
        vm.active_fields = {};
        vm.allRoles = {};
        vm.allWorkingGroups = {};
        vm.roleChecked = {};
        vm.feesToPay = {};
        vm.avatar = "";

        // Methods
        vm.getMemberProfile = function() {
            var data = {};
            data.is_ui = 1;
            if($stateParams.seo != '') {
                data.seo_url = $stateParams.seo;
            }

            $('#loadingOverlay').show();
            $http({
                method: 'GET',
                url: "/api/getMemberProfile",
                params: data
            }).then(function successCallback(response) {
                    if(response.data.success == 0) {
                        $state.go('app.dashboard');
                    }
                    vm.member = response.data.member;
                    console.log(vm.member);
                    vm.workingGroups = response.data.workingGroups;
                    vm.board_positions = response.data.board_positions;
                    vm.roles = response.data.roles;
                    vm.fees_paid = response.data.fees_paid;
                    vm.active_fields = response.data.active_fields;
                    $scope.bio = $sce.trustAsHtml(vm.member.bio);
                    vm.avatar = "/api/getMemberAvatar/"+vm.member.id+"?"+new Date().getTime();

                    setTimeout(vm.fixUiHeights, 500);
            }, function errorCallback() {
                $state.go('app.dashboard');
                $('#loadingOverlay').hide();
            })
        }

        vm.uploader = new FileUploader();
        vm.uploader.url = "/api/uploadMemberAvatar";
        vm.uploader.alias = "avatar";
        vm.uploader.autoUpload = true;
        vm.uploader.headers = {
            'X-Auth-Token': xAuthToken
        }
        vm.uploader.onCompleteAll = function(response) {
            // location.reload();
            vm.getMemberProfile();
        }

        vm.getRoles = function() {
            $http({
                method: 'GET',
                url: "/api/getRoles"
            }).then(function successCallback(response) {
                vm.allRoles = response.data.rows;
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

        vm.initControls = function() {
            $('#startDateBoard, #endDateBoard, #startDateWg, #endDateWg').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $("#department").select2({width: '100%'});
            $("#workgroup").select2({width: '100%'});
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

        vm.getWorkingGroups = function() {
            $http({
                method: 'GET',
                url: '/api/getWorkingGroups'
            })
            .then(function successCallback(response) {
                vm.allWorkingGroups = response.data;
            });
        }


        vm.addBoardMembership = function() {
            $http({
                method: "POST",
                url: '/api/setBoardPosition',
                data: {
                    member_id: vm.member.id,
                    department_id: $('#department').val(),
                    start_date: $('#startDateBoard').val(),
                    end_date: $('#endDateBoard').val()
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Board membership added successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addBoardModal').modal('hide');
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

        vm.addWorkingGroup = function() {
            $http({
                method: "POST",
                url: '/api/addWorkingGroupToMember',
                data: {
                    member_id: vm.member.id,
                    work_group_id: $('#workgroup').val(),
                    start_date: $('#startDateWg').val(),
                    end_date: $('#endDateWg').val()
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Member added to working group successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addWorkGroupModal').modal('hide');
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

        vm.addRoles = function() {
            $http({
                method: "POST",
                url: '/api/addMemberRoles',
                data: {
                    member_id: vm.member.id,
                    roles: vm.roleChecked
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Role(s) added successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addRolesModal').modal('hide');
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

        vm.addFees = function() {
            vm.feesPaid = {};
            $.each(vm.fees, function(key, val) {
                console.log(val);
                vm.feesPaid[val.cell[0]] = $('#feepaid_'+val.cell[0]).val();
            });

            $http({
                method: "POST",
                url: '/api/addFeesToMember',
                data: {
                    member_id: vm.member.id,
                    feesPaid: vm.feesPaid,
                    fees: vm.feesToPay
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Fee(s) added successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addFeesModal').modal('hide');
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

        vm.editBio = function() {
            $http({
                method: "POST",
                url: '/api/editBio',
                data: {
                    bio: vm.member.bio
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Bio saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#editBioModal').modal('hide');
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

        vm.changeEmail = function() {
            $http({
                method: "POST",
                url: '/api/changeEmail',
                data: {
                    email: vm.member.email_change,
                    email_confirmation: vm.member.email_confirmation
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Email changed successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#changeEmailModal').modal('hide');
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

        vm.changePassword = function() {
            $http({
                method: "POST",
                url: '/api/changePassword',
                data: {
                    password: vm.member.password,
                    password_confirmation: vm.member.password_confirmation
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Password changed successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });

                    $('#changePasswordModal').modal('hide');
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

        vm.deleteFee = function(id) {
            if(!confirm("Are you sure you want to delete this fee?")) {
                return;
            }

            $http({
                method: "POST",
                url: '/api/deleteFee',
                data: {
                    id: id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Fee deleted successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addRolesModal').modal('hide');
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

        vm.deleteRole = function(id) {
            if(!confirm("Are you sure you want to delete this role?")) {
                return;
            }

            $http({
                method: "POST",
                url: '/api/deleteRole',
                data: {
                    id: id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Role deleted successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addRolesModal').modal('hide');
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

        vm.deleteMembership = function(id) {
            if(!confirm("Are you sure you want to delete this membership?")) {
                return;
            }

            $http({
                method: "POST",
                url: '/api/deleteMembership',
                data: {
                    id: id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Membership deleted successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addRolesModal').modal('hide');
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

        vm.deleteWorkGroup = function(id) {
            if(!confirm("Are you sure you want to delete this workgroup?")) {
                return;
            }

            $http({
                method: "POST",
                url: '/api/deleteWorkGroup',
                data: {
                    id: id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Workgroup deleted successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#addRolesModal').modal('hide');
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

        vm.suspendAccount = function() {
            $http({
                method: "POST",
                url: '/api/suspendAccount',
                data: {
                    reason: vm.reason,
                    id: vm.member.id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Account suspended successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#suspendAccountModal').modal('hide');
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

        vm.unsuspendAccount = function() {
            if(!confirm("Are you sure you want to unsuspend this account? Account suspension will not be lifted if the member was suspended by the System!")) {
                return;
            }
            $http({
                method: "POST",
                url: '/api/unsuspendAccount',
                data: {
                    id: vm.member.id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Account unsuspended successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.getMemberProfile();
                    $('#suspendAccountModal').modal('hide');
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

        vm.impersonate = function() {
            if(!confirm("Are you sure you want to impersonate this account? You will need to logout afterwards to be able to login back to your own account!")) {
                return;
            }
            $http({
                method: "POST",
                url: '/api/impersonateMember',
                data: {
                    id: vm.member.id
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    location.reload();
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

        vm.fixUiHeights = function() {
            $('#bigAvatar').height($('#bigAvatar').width());

            var boardHeight = $('#boardTable').height();
            var rolesHeight = $('#rolesTable').height();
            var feesHeight = $('#feesTable').height();

            var maxValue = Math.max(boardHeight, rolesHeight, feesHeight);

            $('#boardTable').height(maxValue);
            $('#rolesTable').height(maxValue);
            $('#feesTable').height(maxValue);

            $('#loadingOverlay').hide();
        }
        ///////
        vm.getMemberProfile();
        vm.getRoles();
        vm.getFees();
        vm.getDepartments();
        vm.getWorkingGroups();
        vm.initControls();
    }

})();
