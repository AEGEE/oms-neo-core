(function ()
{
    'use strict';

    angular
        .module('app.dashboard', [])
        .config(config)
        .controller('DashboardController', DashboardController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.dashboard', {
                url: '/',
                data: {'pageTitle': 'Dashboard'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/dashboard/dashboard.php',
                        controller: 'DashboardController as vm'
                    }
                }
            });
    }

    function DashboardController($http, $state) {
        // Data
        var vm = this;
        vm.users = 0;
        vm.newestMembers = {};
        vm.latestNews = {};
        vm.news = {};

        vm.what = "Preview";

        // Methods
        vm.getDashboardData = function() {
            $('#loadingOverlay').show();
            $http({
                method: 'GET',
                url: "/api/getDashboardData"
            }).then(function successCallback(response) {
                vm.users = response.data.userCount;
                vm.newestMembers = response.data.newestMembers;
                vm.latestNews = response.data.latestNews;
                $('#loadingOverlay').hide();
            });
        }

        vm.getNews = function() {
            $('#loadingOverlay').show();
            $http({
                method: 'GET',
                url: "/api/getNews",
                params: {
                    sidx: 'date',
                    sord: 'desc',
                    rows: 5
                }
            }).then(function successCallback(response) {
                vm.latestNews = response.data;
                $('#loadingOverlay').hide();
            });
        }

        vm.closeAndReset = function() {
            $('#newsModal').modal('hide');
            $('#newsContent').show();
            $('#previewNews').hide();
            vm.what = "Preview";
            vm.news = {};
        }

        vm.previewNews = function() {
            if($('#previewNews').is(':visible')) {
                $('#newsContent').show();
                $('#previewNews').hide();
                vm.what = "Preview";
            } else {
                $('#newsContent').hide();
                $('#previewNews').show();
                vm.what = "Edit";
            }
        }

        vm.saveNews = function() {
            $http({
                method: 'POST',
                url: "/api/saveNews",
                data: vm.news
            }).then(function successCallback(response) {
                if(response.data.success == 1) {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'News saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.getNews();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: 'Please try again!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            });
        }

        vm.editNews = function(id) {
            $http({
                method: 'GET',
                url: "/api/getNewsById",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == 0) {
                    $.gritter.add({
                        title: 'Error!',
                        text: 'Please try again!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    return;
                }
                vm.news = response.data.news;
                $('#newsModal').modal('show');
            });
        }

        vm.deleteNews = function(id) {
            if(!confirm("Are you sure you want to delete this news item?")) {
                return;
            }
            $http({
                method: 'POST',
                url: "/api/deleteNews",
                data: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == 0) {
                    $.gritter.add({
                        title: 'Error!',
                        text: 'Please try again!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    return;
                }
                $.gritter.add({
                    title: 'Success!',
                    text: 'News deleted successfully!',
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class'
                });
                vm.getNews();
            });
        }

        vm.goToNews = function(id) {
            $state.go('app.newsItem', {
                'newsId': id
            });
        }

        ///////
        vm.getDashboardData();
    }

})();