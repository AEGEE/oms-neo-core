(function ()
{
    'use strict';
    
    // Not a ui-router state but a service, thus missing a config
    omsApp
        .controller('LoginModalController', LoginModalController);

    function LoginModalController($scope, $http) {

        /* In case we already have a token, try if we can use that one
        if(window.localStorage.getItem('X-Auth-Token')) {
            $http({
                method: 'POST',
                url: '/api/tokens/user',
                data: {
                    token: window.localStorage.getItem("X-Auth-Token")
                },
                headers: {
                    "X-Auth-Token": window.localStorage.getItem("X-Auth-Token")
                }
            }).then((response) => {
                $scope.$close(response.data.data);
            }).catch((err) => {
                // didn't work, do nothing and proceed with the form
                window.localStorage.removeItem("X-Auth-Token");
            });
        }*/

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
                    $.ajaxSetup({headers: { 'X-Auth-Token': token }});

                    $http({
                        method: 'POST',
                        url: '/api/tokens/user',
                        data: {
                            token: localStorage.getItem("X-Auth-Token")
                        }
                    })
                    .then(function successCallback(response) {
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