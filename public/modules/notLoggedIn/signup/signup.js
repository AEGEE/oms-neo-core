(function ()
{
    'use strict';

    angular
        .module('public.signup', [])
        .config(config)
        .controller('SignupController', SignupController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('public.signup', {
                url: '/register',
                data: {'pageTitle': 'Signup'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/signup/signup.php',
                        controller: 'SignupController as vm'
                    }
                }
            });
    }

    function SignupController($http, $stateParams, $state) {
        // Data
        var vm = this;
        vm.user = {};

        vm.customFields = [];

        vm.genderTypes = [ 'male', 'female', 'other'];

        vm.registrationFields = {};

        vm.checkCampaignExists = function() {
            $('#loadingOverlay').show();
            $http({
                method: 'GET',
                url: "/api/checkCampaignExists",
                params: {
                    link: $stateParams.link
                }
            }).then(function successCallback(response) {
                    if(response.data.success == 0) {
                        $state.go('app.login');
                    }
                    vm.customFields = response.data.customFields;
                    $('#loadingOverlay').hide();
                    console.log(vm.customFields);

            }, function errorCallback() {
                $state.go('app.login');
                $('#loadingOverlay').hide();
            })
        }

        vm.getRegistrationFields = function() {
            $http({
                method: 'GET',
                url: '/api/getRegistrationFields'
            })
            .then(function successCallback(response) {
                vm.registrationFields = response.data;
            });
        }

        vm.initControls = function() {
            // $("#wizard").bwizard();
            $("#wizard").bwizard({ validating: function (e, ui) {
                    if (ui.index == 0) {
                        // step-1 validation
                        if (false === $('form[name="signupWizard"]').parsley().validate('wizard-step-1')) {
                            return false;
                        }
                    } else if (ui.index == 1) {
                        // step-2 validation
                        if (false === $('form[name="signupWizard"]').parsley().validate('wizard-step-2')) {
                            return false;
                        }
                    } else if (ui.index == 2) {
                        // step-3 validation
                        if (false === $('form[name="signupWizard"]').parsley().validate('wizard-step-3')) {
                            return false;
                        }
                    }
                }
            });

            $('#date_of_birth').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        }

        vm.signup = function() {
            if (false === $('form[name="signupWizard"]').parsley().validate('wizard-step-3')) {
                return false;
            }

            vm.user.studies_type = $('#studies_type').val();
            vm.user.study_field = $('#study_field').val();
            vm.user.date_of_birth = '1990-05-25';
            vm.user.address_id = 1;

            vm.user.fields = vm.customFields;
            vm.user.link = $stateParams.link;

            $('#loadingOverlay').show();
            console.log(vm.user);
            $http({
                method: 'POST',
                url: '/api/users',
                data: vm.user
            })
            .then(function successCallback(response) {
                if(response.data.success == true) {
                    // all good
                    $('#signupWizardForm').hide();
                    $('#signupSuccessful').show();
                    $('#loadingOverlay').hide();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    $('#loadingOverlay').hide();
                }
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log(response);
          });
        }

        vm.getRegistrationFields();
        $("#antenna, #studies_type, #study_field").select2({width: '100%'});

        setTimeout(vm.initControls, 10);
    }

})();
