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
                                        Contact Information
                                    </li>
                                    <li>
                                        Other information
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
                                                    <label for="date_of_birth">Date of birth *</label>
                                                    <input id="date_of_birth" type="text" name="date_of_birth" placeholder="date of birth" class="form-control" ng-model="vm.user.date_of_birth" data-parsley-group="wizard-step-1" required />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="gender">Gender *</label>
                                                    <select id="gender" class="form-control" ng-model="vm.user.gender" ng-options="value.id as value.name for value in vm.genderTypes track by value.id" data-parsley-group="wizard-step-1" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </fieldset>
                                </div>
                                <!-- end wizard step-1 -->
                                <!-- begin wizard step-2 -->
                                <div>
                                    <fieldset>
                                        <legend class="pull-left width-full">Contact Information</legend>
                                        <!-- begin row -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email address *</label>
                                                    <input id="email" type="email" name="email" placeholder="Email address" class="form-control" ng-model="vm.user.contact_email" data-parsley-group="wizard-step-2" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email_validated">Email address repeat *</label>
                                                    <input id="email_validated" type="email" name="email_validated" placeholder="Email address repeat" class="form-control" ng-model="vm.user.contact_email_confirmation" data-parsley-group="wizard-step-2" required />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input id="phone" type="text" name="phone" placeholder="phone address" class="form-control" ng-model="vm.user.phone" data-parsley-group="wizard-step-2" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input id="address" type="text" name="address" placeholder="address" class="form-control" ng-model="vm.user.address" data-parsley-group="wizard-step-2" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input id="city" type="text" name="city" placeholder="city" class="form-control" ng-model="vm.user.city" data-parsley-group="wizard-step-2" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="zipcode">Zip code</label>
                                                    <input id="zipcode" type="text" name="zipcode" placeholder="zipcode" class="form-control" ng-model="vm.user.zipcode" data-parsley-group="wizard-step-2" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </fieldset>
                                </div>
                                <!-- end wizard step-2 -->
                                <!-- begin wizard step-3 -->
                                <div>
                                    <fieldset>
                                        <legend class="pull-left width-full">Other information</legend>
                                        <!-- begin row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="antenna">Antenna *</label>
                                                    <select class="form-control" id="antenna" ng-model="vm.user.antenna_id" data-parsley-group="wizard-step-3" ng-options="antenna.id as antenna.name for antenna in vm.registrationFields.antennae track by antenna.id" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="university">University *</label>
                                                    <input id="university" type="text" name="university" placeholder="university" class="form-control" ng-model="vm.user.university" data-parsley-group="wizard-step-3" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="studies_type">Study type *</label>
                                                    <select class="form-control" id="studies_type" ng-model="vm.user.studies_type" data-parsley-group="wizard-step-3" ng-options="type.id as type.name for type in vm.registrationFields.study_type track by type.id" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="study_field">Study field *</label>
                                                    <select class="form-control" id="study_field" ng-model="vm.user.study_field" data-parsley-group="wizard-step-3" ng-options="field.id as field.name for field in vm.registrationFields.study_field track by field.id" required>
                                                        <option></option>
                                                    </select>
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