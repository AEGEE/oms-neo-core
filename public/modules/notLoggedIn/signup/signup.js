(function ()
{
    'use strict';

    angular
        .module('app.signup', [])
        .config(config)
        .controller('SignupController', SignupController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.signup', {
                url: '/signup',
                data: {'pageTitle': 'Signup'},
                views   : {
                    'main@'         : {
                        templateUrl: 'modules/notLoggedIn/signup/signup.html',
                        controller: 'SignupController as vm'
                    }
                }
            });
    }

    function SignupController($http) {
        // Data
        var vm = this;
        vm.user = {};

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

        vm.registrationFields = {};

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
                $('#loadingOverlay').hide();
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

            vm.user.antenna_id = $('#antenna').val();
            vm.user.studies_type = $('#studies_type').val();
            vm.user.study_field = $('#study_field').val();
            vm.user.date_of_birth = $('#date_of_birth').val();

            $('#loadingOverlay').show();
            $http({
                method: 'POST',
                url: '/api/signup',
                data: vm.user
            })
            .then(function successCallback(response) {
                if(response.data.success == 1) {
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
                $('#loadingOverlay').hide();
            });
        }

        // FormWizard.init();
        // setTimeout(
        //     $("#wizard").bwizard(), 3000
        // )
        vm.getRegistrationFields();
        $("#antenna, #studies_type, #study_field").select2({width: '100%'});

        setTimeout(vm.initControls, 10);
    }

})();