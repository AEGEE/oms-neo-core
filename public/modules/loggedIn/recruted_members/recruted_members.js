(function ()
{
    'use strict';

    angular
        .module('app.recruted_members', [])
        .config(config)
        .controller('RecruitedUsersController', RecruitedUsersController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.recruted_members', {
                url: '/recruted_members',
                data: {'pageTitle': 'Recruited members'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/recruted_members/recruted_members.php',
                        controller: 'RecruitedUsersController as vm'
                    }
                }
            });
    }

    function RecruitedUsersController($http, $compile, $scope, $sce) {
        // Data
        var vm = this;
        vm.filter = {};
        vm.what = "Preview";

        vm.antennae = {};
        vm.campaigns = {};

        vm.roles = {};
        vm.fees = {};
        vm.departments = {};

        vm.currentUser = {};

        vm.is_superadmin = isSuperAdmin;

        vm.statuses = [
            {
                id: 0,
                name: 'Opened'
            }, {
                id: -1,
                name: 'Rejected'
            }, {
                id: 1,
                name: 'In progress'
            }, {
                id: 2,
                name: 'Accepted'
            }
        ];

        var grid_container = "#gridContainer";
        var grid_pager = "#gridPager";
        // Methods

        vm.loadGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1
            };

            params.url = "/api/getRecruitedUsers";
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
                'Signed up at',
                'Body',
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
                    name: 'created_at',
                    index: 'created_at',
                    width: 100
                }, {
                    name: 'antenna',
                    index: 'antenna',
                    sortable: false,
                    hidden: !vm.is_superadmin,
                    width: 100
                }, {
                    name: 'status',
                    index: 'status',
                    sortable: false,
                    width: 100
                }
            ];
            params.rowNum = 25;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = grid_pager;
            params.sortname = 'start_date';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Users";

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

            object.is_grid = 1;
            jQuery(grid_container).jqGrid('setPostData', object);
            jQuery(grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
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

        vm.getCampaigns = function() {
            $http({
                method: 'GET',
                url: '/api/getRecruitementCampaigns',
                params: {
                    limit: 100000
                }
            })
            .then(function successCallback(response) {
                vm.campaigns = response.data;
            });
        }

        vm.getUserDetails = function(userId, openModal) {
            $http({
                method: 'GET',
                url: '/api/getUserDetails',
                params: {
                    id: userId
                }
            })
            .then(function successCallback(response) {
                vm.currentUser = response.data;
                $scope.status = $sce.trustAsHtml(vm.currentUser.status);
                vm.hideCommentArea();
                if(openModal) {
                    $('#userDetailsModal').modal('show');
                }
            });
        }

        vm.hideCommentArea = function() {
            $('#commentArea').hide();
            $('#addCommBtn').show();
            vm.comment = "";
        }

        vm.showCommentArea = function() {
            $('#commentArea').show();
            $('#addCommBtn').hide();
        }

        vm.addComment = function() {
            $http({
                method: 'POST',
                url: '/api/addComment',
                data: {
                    user_id: vm.currentUser.id,
                    comment: vm.comment
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == 1) {
                    vm.getUserDetails(vm.currentUser.id);
                } else {
                    $.gritter.add({
                    title: 'Error!',
                    text: "An error occoured",
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
                }
            });
        }

        vm.changeStatus = function(newStatus) {
            if(newStatus == 2) { // accepted.. launch acception modal..
                vm.activateUser();
                $('#userDetailsModal').modal('hide');
                return;
            }
            $http({
                method: 'POST',
                url: '/api/changeStatus',
                data: {
                    user_id: vm.currentUser.id,
                    newStatus: newStatus
                }
            })
            .then(function successCallback(response) {
                if(response.data.success == 1) {
                    vm.getUserDetails(vm.currentUser.id);
                } else {
                    $.gritter.add({
                    title: 'Error!',
                    text: "An error occoured",
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
                }
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

        vm.getRoles = function() {
            $http({
                method: 'GET',
                url: "/api/getRoles"
            }).then(function successCallback(response) {
                vm.roles = response.data.rows;
            })
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
            $('#activateUserModal').modal('show');
        }

        vm.closeAndResetActivate = function() {
            $('#activateUserModal').modal('hide');
            $('.paidOnDateFee').val("");
        }

        vm.addContoltoFee = function() {
            $('.paidOnDateFee').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }
        vm.createAndActivateUser = function() {
            vm.currentUser.feesPaid = {};
            $.each(vm.fees, function(key, val) {
                vm.currentUser.feesPaid[val.cell[0]] = $('#feepaid_'+val.cell[0]).val();
            });
            $http({
                method: "POST",
                url: '/api/activateUserRecruited',
                data: vm.currentUser,
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

        vm.exportGrid = function() {
            vm.filter.export = 1;
            $http({
                url: 'api/getRecruitedUsers',
                method: 'GET',
                responseType: 'arraybuffer',
                params: vm.filter,
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

        vm.previewComment = function() {
            if($('#previewComment').is(':visible')) {
                $('#newComment').show();
                $('#previewComment').hide();
                vm.what = "Preview";
            } else {
                $('#newComment').hide();
                $('#previewComment').show();
                vm.what = "Edit";
            }
        }

        ///////
        vm.loadGrid();
        vm.getBodies();
        vm.getCampaigns();

        // Needed for activation..
        vm.getDepartments();
        vm.getRoles();
        vm.getFees();
    }

})();
