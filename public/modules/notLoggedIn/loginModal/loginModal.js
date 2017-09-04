(function ()
{
    'use strict';
    
    // Not a ui-router state but a service, thus missing a config
    omsApp
    .controller('LoginModalController', LoginModalController)
    .service('loginModal', LoginModalService);

    function LoginModalService($modal) {
        
        var loginModalConfig = {
            loading: false,
            promise: undefined
        };

        var assignCurrentUser = function(user) {
            loginModalConfig.loading = false;
            return user;
        };
        
        return function() {
            // Either return promise of already created modal
            if(loginModalConfig.loading) {
                return loginModalConfig.promise.then(function(user) {
                    return user;
                });
            }
            // Or create a new modal
            else {

                loginModalConfig.loading = true;

                var instance = $modal.open({
                    templateUrl: 'modules/notLoggedIn/loginModal/loginModal.html',
                    controller: 'LoginModalController as vm'
                });

                loginModalConfig.promise = instance.result;

                return instance.result.then(assignCurrentUser);
            }
        };
    };

    function LoginModalController($rootScope, $scope, $http) {

        this.cancel = $scope.$dismiss;

        this.submit = function (email, password) {
            $http({
                method: 'POST',
                url: '/api/login',
                data: {
                    username: email,
                    password: password
                }
            }).then((response) => {
                if(response.data.success == 1) {
                    // Store in local storage
                    window.localStorage.setItem("X-Auth-Token", response.data.data);
                    $http.defaults.headers.common['X-Auth-Token'] = response.data.data;
                    $.ajaxSetup({headers: { 'X-Auth-Token': response.data.data }});
                    $http({
                        method: 'POST',
                        url: '/api/tokens/user',
                        data: {
                            token: localStorage.getItem("X-Auth-Token")
                        }
                    })
                    .then(function successCallback(response) {
                        $rootScope.currentUser = response.data.data;
                        $scope.$close(response.data.data);
                    }).catch(function(err) {
                        $.gritter.add({
                            title: 'Login error!',
                            text: 'Could not fetch user data',
                            sticky: true,
                            time: 3600,
                            class_name: 'my-sticky-class'
                        });
                    });
                }
                else { 
                    $.gritter.add({
                        title: 'Login error!',
                        text: 'Username / password invalid',
                        sticky: true,
                        time: 3600,
                        class_name: 'my-sticky-class'
                    });
                }
            }).catch((err) => {
                $.gritter.add({
                    title: 'Login error!',
                    text: 'Username / password invalid',
                    sticky: true,
                    time: 3600,
                    class_name: 'my-sticky-class'
                }); 
            });
        };
    }

})();