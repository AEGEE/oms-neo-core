<?php
session_start();
$options = $_SESSION['globals'];
session_write_close();
?>
<div>
	<div class="login-cover">
	    <div class="login-cover-image"><img src="assets/img/login-bg/bg-7.jpg" data-id="login-cover-image" alt="" /></div>
	    <div class="login-cover-bg"></div>
	</div>
	<div class="login login-v2 signup" data-pageload-addclass="animated fadeIn">
        <!-- begin brand -->
        <div class="login-header">
            <div class="brand">
                <span class="logo"></span> <?=$options['app_name']?>
                <small>Signup</small>
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </div>
        <!-- end brand -->

        <!-- begin row -->
        <div id="signupFormX" class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse">
                    <div class="panel-body">
                        <div id="signupSuccessful" class="hiddenItem">
                            <div class="jumbotron m-b-0 text-center color-b">
                                <h1>Signup successful</h1>
                                <p>Your account will be activated soon. You will receive a mail containing the details once activated!</p>
                            </div>
                        </div>
                        <form id="signupWizardForm" action="/" method="POST" data-parsley-validate="true" name="signupWizard">
                            <div id="wizard">
                                <ol>
                                    <li>
                                        Personal information
                                    </li>
                                    <li>
                                        Finishing up
                                    </li>
                                </ol>
                                <!-- begin wizard step-1 -->
                                <div>
                                    <fieldset>
                                        <legend class="pull-left width-full">Personal information</legend>
                                        <!-- begin row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">First name *</label>
                                                    <input id="first_name" type="text" name="firstname" placeholder="First name" class="form-control" ng-model="vm.user.first_name" data-parsley-group="wizard-step-1" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name">Last name *</label>
                                                    <input id="last_name" type="text" name="lastname" placeholder="Last name" class="form-control" ng-model="vm.user.last_name" data-parsley-group="wizard-step-1" required />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="gender">Gender *</label>
                                                    <select id="gender" class="form-control" ng-model="vm.user.gender" ng-options="value for value in vm.genderTypes" data-parsley-group="wizard-step-1" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                        <!-- begin row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email address *</label>
                                                    <input id="email" type="email" name="email" placeholder="Email address" class="form-control" ng-model="vm.user.personal_email" data-parsley-group="wizard-step-1" required />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </fieldset>
                                </div>
                                <!-- end wizard step-1 -->

                                <!-- begin wizard step-3 -->
                                <div>
                                    <fieldset>
                                        <legend class="pull-left width-full">Other information</legend>
                                        <!-- begin row -->
                                        <div class="row">
                                            <div class="col-md-12" ng-repeat="field in vm.customFields">
                                                <div class="form-group">
                                                    <label>{{field.name}}</label>
                                                    <div ng-if="field.type == 1">
                                                        <input type="text" placeholder="{{field.name}}" ng-model="field.value" class="form-control" data-parsley-group="wizard-step-3" required />
                                                    </div>
                                                    <div ng-if="field.type == 2">
                                                        <textarea placeholder="{{field.name}}" class="form-control" ng-model="field.value" data-parsley-group="wizard-step-3" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button ng-disabled="signupWizard.$pristine" class="btn btn-block btn-lg btn-success" onclick="return false" ng-click="vm.signup()">Submit registration!</button>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </fieldset>
                                </div>
                                <!-- end wizard step-3 -->
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-12 -->
        </div>
    </div>
</div>
