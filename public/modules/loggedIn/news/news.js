(function ()
{
    'use strict';

    angular
        .module('app.news', [])
        .config(config)
        .controller('NewsController', NewsController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.news', {
                url: '/news',
                data: {'pageTitle': 'News'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/news/news.php',
                        controller: 'NewsController as vm'
                    }
                }
            })
            .state('app.newsItem', {
                url: '/news/:newsId',
                data: {'pageTitle': 'News'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/news/singleNews.php',
                        controller: 'NewsController as vm'
                    }
                }
            });
    }

    function NewsController($http, $stateParams, $state) {
        // Data
        var vm = this;
        vm.latestNews = {};
        vm.news = {};

        vm.what = "Preview";

        // Methods
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

        vm.getSingleNews = function(id) {
            $('#loadingOverlay').show();
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

        vm.saveNews = function(x) {
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
                    if(!x) {
                        vm.closeAndReset();
                        vm.getNews();
                    } else {
                        $('#newsModal').modal('hide');
                    }
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
            if(!id) {
                $('#newsModal').modal('show');
                return;
            }
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

        vm.deleteNewsFromPage = function(id) {
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
                $state.go('app.news');
            });
        }

        vm.goToNews = function(id) {
            $state.go('app.newsItem', {
                'newsId': id
            });
        }

        ///////
        if(!$stateParams.newsId) {
            vm.getNews();
        } else {
            vm.getSingleNews($stateParams.newsId);
        }
    }

})();