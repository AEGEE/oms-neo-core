<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">Home</a></li>
        <li class="active">Profile</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">{{vm.user.fullname}} <br /><small>{{vm.user.rank}}</small></h1>
    <!-- end page-header -->
    <!-- begin profile-container -->
    <div class="profile-container">
        <!-- begin profile-section -->
        <div class="profile-section">
        	<div class="row">
	            <!-- begin profile-left -->
	            <div class="col-md-2">
	                <!-- begin profile-image -->
	                <div class="profile-image" id="bigAvatar">
	                    <img style="width: 100%" ng-src="{{vm.avatar}}" />
	                    <i class="fa fa-user hide"></i>
	                </div>
	                <!-- end profile-image -->
	                <div class="m-b-10">
	                	<!-- <h5>Actions</h5> -->
	                    <button class="btn btn-warning btn-block btn-sm" ng-show="vm.active_fields.change_avatar "onclick="$('#uploaderBtn').click()"><i class="fa fa-photo"></i> Change avatar</button>
	                    <input style="display:none" id="uploaderBtn" class="hiddenElement" type="file" nv-file-select="" uploader="vm.uploader" />
	                    <button class="btn btn-danger btn-block btn-sm" ng-show="vm.active_fields.change_password" data-toggle="modal" data-target="#changePasswordModal"><i class="fa fa-lock"></i> Change password</button>
	                    <button class="btn btn-success btn-block btn-sm" ng-show="vm.active_fields.change_email" data-toggle="modal" data-target="#changeEmailModal"><i class="fa fa-envelope"></i> Change email</button>
	                    <button class="btn btn-danger btn-block btn-sm" ng-show="vm.active_fields.get_ssl_tls" ng-click="vm.getSSL()"><i class="fa fa-certificate"></i> Get SSL/TLS certificate for login</button>
	                    <div ng-show="vm.active_fields.account_info">
		                    <h5 class="text-center"><b>Account info</b></h5>
		                    <table class="table">
		                    	<tr>
		                    		<th>
		                    			Status
		                    		</th>
		                    		<td>
		                    			{{vm.user.status}}
		                    		</td>
		                    	</tr>
		                    	<tr>
		                    		<th>
		                    			Date activated
		                    		</th>
		                    		<td>
		                    			{{vm.user.activated_at}}
		                    		</td>
		                    	</tr>
		                    	<tr ng-show="vm.active_fields.suspended">
		                    		<th>
		                    			Suspended for
		                    		</th>
		                    		<td>
		                    			{{vm.user.suspended_for}}
		                    		</td>
		                    	</tr>
		                    </table>
		                    <button class="btn btn-danger btn-block btn-sm" ng-show="vm.active_fields.suspend_account" data-toggle="modal" data-target="#suspendAccountModal">Suspend account</button>
		                    <button class="btn btn-success btn-block btn-sm" ng-show="vm.active_fields.unsuspend_account" ng-click="vm.unsuspendAccount()">Unsuspend account</button>
		                    <button class="btn btn-warning btn-block btn-sm" ng-show="vm.active_fields.impersonate" ng-click="vm.impersonate()">Impersonate user</button>
	                   </div>
	                </div>
	            </div>
	            <!-- end profile-left -->
	            <!-- begin profile-right -->
	            <div class="col-md-10">
	            	<div class="row">
	            		<div class="col-md-6">
			                <!-- begin profile-info -->
			                <div class="profile-info">
			                    <!-- begin table -->
			                    <div class="table-responsive">
			                        <table class="table table-profile">
<!-- 			                        	<thead>
					                        <tr>
					                            <th colspan="2">
					                                <h4>{{vm.user.fullname}} <small>{{vm.user.rank}}</small></h4>
					                            </th>
					                        </tr>
					                    </thead> -->
			                            <tbody>
			                                <tr class="highlight">
			                                    <td class="field">Antenna</td>
			                                    <td>{{vm.user.antenna}}</td>
			                                </tr>
			                                <tr>
			                                    <td class="field">Antenna city</td>
			                                    <td>{{vm.user.antenna_city}}</td>
			                                </tr>
			                                <tr>
			                                    <td class="field">Country</td>
			                                    <td>{{vm.user.country}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">Department</td>
			                                    <td>{{vm.user.department}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">Email</td>
			                                    <td>{{vm.user.email}}</td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <!-- end table -->
			                </div>
			                <!-- end profile-info -->
			            </div>
			            <div class="col-md-6">
			                <!-- begin profile-info -->
			                <div class="profile-info">
			                    <!-- begin table -->
			                    <div class="table-responsive">
			                        <table class="table table-profile">
			                            <tbody>
			                                <tr class="highlight">
			                                    <td class="field">Date of birth</td>
			                                    <td>{{vm.user.date_of_birth}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">Gender</td>
			                                    <td>{{vm.user.gender}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">University</td>
			                                    <td>{{vm.user.university}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">Studies</td>
			                                    <td>{{vm.user.studies}}</td>
			                                </tr>
			                                <tr class="highlight">
			                                    <td class="field">City</td>
			                                    <td>{{vm.user.city}}</td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <!-- end table -->
			                </div>
			                <!-- end profile-info -->
			            </div>
		            </div>
		            <hr />
		            <div class="row">
		            	<div class="col-md-6">
		            		<h4>Bio <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#editBioModal" ng-show="vm.active_fields.change_bio"><i class="fa fa-pencil"></i></button></h4>
							<div ng-bind-html="bio"></div>
		            	</div>
		            	<div class="col-md-6" ng-show="vm.active_fields.work_groups">
		            		<h4>Working groups <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#addWorkGroupModal" ng-show="vm.active_fields.addEditStuff"><i class="fa fa-plus"></i></button></h4>
							<table class="table">
								<tr>
									<th ng-show="vm.active_fields.addEditStuff">Actions</th>
									<th>Name</th>
									<th>Period</th>
								</tr>
								<tr ng-repeat="workGroup in vm.workingGroups">
									<td ng-show="vm.active_fields.addEditStuff">
										<button class='btn btn-default btn-xs clickMeProfile' title='Delete' ng-click='vm.deleteWorkGroup(workGroup.id)'><i class='fa fa-ban'></i></button>
									</td>
									<td>
										{{workGroup.name}}
									</td>
									<td>
										{{workGroup.period}}
									</td>
								</tr>
							</table>
		            	</div>
		            </div>
		            <hr />
		            <div class="row">
		            	 <!-- begin col-4 -->
		                <div class="col-md-4" ng-show="vm.active_fields.board_positions">
		                    <h4 class="title">Board positions <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#addBoardModal" ng-show="vm.active_fields.addEditStuff"><i class="fa fa-plus"></i></button></h4>
		                    <!-- begin scrollbar -->
		                    <div id="boardTable" data-scrollbar="false" data-height="280px" class="bg-silver well">
		                        <!-- begin table -->
		                        <table class="table table-condensed">
		                            <thead>
		                                <tr>
		                                    <th ng-show="vm.active_fields.addEditStuff">Actions</th>
		                                    <th>Department</th>
		                                    <th>Period</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr ng-repeat="position in vm.board_positions">
											<td ng-show="vm.active_fields.addEditStuff">
												<button class='btn btn-default btn-xs clickMeProfile' title='Delete' ng-click='vm.deleteMembership(position.id)'><i class='fa fa-ban'></i></button>
											</td>
											<td>
												{{position.name}}
											</td>
											<td>
												{{position.period}}
											</td>
										</tr>
		                            </tbody>
		                        </table>
		                        <!-- end table -->
		                    </div>
		                    <!-- end scrollbar -->
		                </div>
		                <!-- end col-4 -->
		                <!-- begin col-4 -->
		                <div class="col-md-4" ng-show="vm.active_fields.role">
		                    <h4 class="title">Roles <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#addRolesModal" ng-show="vm.active_fields.addEditStuff"><i class="fa fa-plus"></i></button></h4>
		                    <!-- begin scrollbar -->
		                    <div id="rolesTable" data-scrollbar="false" data-height="280px" class="bg-silver well">
		                        <!-- begin table -->
		                        <table class="table table-condensed">
		                            <thead>
		                                <tr>
		                                    <th ng-show="vm.active_fields.addEditStuff">Actions</th>
		                                    <th>Name</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr ng-repeat="role in vm.roles">
											<td ng-show="vm.active_fields.addEditStuff">
												<button class='btn btn-default btn-xs clickMeProfile' title='Delete' ng-click='vm.deleteRole(role.id)'><i class='fa fa-ban'></i></button>
											</td>
											<td>
												{{role.name}}
											</td>
										</tr>
		                            </tbody>
		                        </table>
		                        <!-- end table -->
		                    </div>
		                    <!-- end scrollbar -->
		                </div>
		                <!-- end col-4 -->
		                <!-- begin col-4 -->
		               	<div class="col-md-4" ng-show="vm.active_fields.addEditStuff">
		                    <h4 class="title">Fees paid <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#addFeesModal"><i class="fa fa-plus"></i></button></h4>
		                    <!-- begin scrollbar -->
		                    <div id="feesTable" data-scrollbar="false" data-height="280px" class="bg-silver well">
		                        <!-- begin table -->
		                        <table class="table table-condensed">
		                            <thead>
		                                <tr>
		                                    <th>Actions</th>
		                                    <th>Name</th>
		                                    <th>Period</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                <tr ng-repeat="fee in vm.fees_paid">
											<td>
												<button class='btn btn-default btn-xs clickMeProfile' title='Delete' ng-click='vm.deleteFee(fee.id)'><i class='fa fa-ban'></i></button>
											</td>
											<td>
												{{fee.name}}
											</td>
											<td>
												{{fee.period}}
											</td>
										</tr>
		                            </tbody>
		                        </table>
		                        <!-- end table -->
		                    </div>
		                    <!-- end scrollbar -->
		                </div>
		                <!-- end col-4 -->
		            </div>
	            </div>
	            <!-- end profile-right -->
	        </div>
        </div>
        <!-- end profile-section -->
    </div>
    <!-- end profile-container -->
</div>

<div class="modal fade" id="addRolesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add roles</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <h4>Roles</h4>
                        <div class="col-md-6"  ng-repeat="role in vm.allRoles">
	                        <div class="form-group m-b-20">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="vm.roleChecked[role.cell[0]]" value="{{role.cell[0]}}"> {{role.cell[1]}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-click="vm.addRoles()"><i class="fa fa-save"></i> Add roles</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addFeesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Fees</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">   
                        <h4>Fees</h4>
                        <div class="alert alert-warning">
                            Fees with no paid no date will be considered being paid today!
                        </div>
                        <div class="form-group m-b-20" ng-repeat="fee in vm.fees">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" ng-model="vm.feesToPay[fee.cell[0]]" value="{{fee.cell[0]}}"> {{fee.cell[1]}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="vm.feesToPay[fee.cell[0]]">
                                    <label>Paid on</label>
                                    <input id="feepaid_{{fee.cell[0]}}" class="form-control paidOnDateFee" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-click="vm.addFees()"><i class="fa fa-save"></i> Add fees</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addBoardModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add board membership</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
	                        <div class="form-group m-b-20">
		                        <label for="department">Department</label>
		                        <select class="form-control" id="department" ng-model="vm.department" ng-options="department.cell[0] as department.cell[1] for department in vm.departments.rows track by department.cell[0]">
		                            <option></option>
		                        </select>
		                    </div>
                        </div>
                        <div class="col-md-6">
                            <label>Starting date</label>
	                        <input id="startDateBoard" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label>Ending date</label>
	                        <input id="endDateBoard" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-click="vm.addBoardMembership()"><i class="fa fa-save"></i> Add membership</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addWorkGroupModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add working group</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
	                        <div class="form-group m-b-20">
		                        <label for="workgroup">Working group</label>
		                        <select class="form-control" id="workgroup" ng-model="vm.workgroup" ng-options="workgroup.cell[0] as workgroup.cell[1] for workgroup in vm.allWorkingGroups.rows track by workgroup.cell[0]">
		                            <option></option>
		                        </select>
		                    </div>
                        </div>
                        <div class="col-md-6">
                            <label>Starting date</label>
	                        <input id="startDateWg" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label>Ending date</label>
	                        <input id="endDateWg" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-click="vm.addWorkingGroup()"><i class="fa fa-save"></i> Add user to working group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editBioModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Edit Bio</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="bio">Bio</label>
	                        <textarea class="form-control" id="bio" ng-model="vm.user.bio"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-click="vm.editBio()"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changePasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change password</h4>
            </div>
            <form name="changePasswordForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="password">Password</label>
	                        <input type="password" name="password" id="password" ng-model="vm.user.password" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation">Password confirm</label>
	                        <input type="password" name="password_confirmation" id="password_confirmation" ng-model="vm.user.password_confirmation" class="form-control" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-disabled="changePasswordForm.$pristine || changePasswordForm.$invalid" ng-click="vm.changePassword()"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changeEmailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change email</h4>
            </div>
            <form name="changeEmailForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Email</label>
	                        <input type="email" name="email" id="email" ng-model="vm.user.email_change" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label for="email_confirmation">Email confirm</label>
	                        <input type="email" name="email_confirmation" id="email_confirmation" ng-model="vm.user.email_confirmation" class="form-control" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-disabled="changeEmailForm.$pristine || changeEmailForm.$invalid" ng-click="vm.changeEmail()"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="suspendAccountModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Suspend account</h4>
            </div>
            <form name="suspendForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="reason">Reason</label>
	                        <textarea name="reason" id="reason" ng-model="vm.reason" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                    <button class="btn btn-sm btn-success" ng-disabled="suspendForm.$pristine || suspendForm.$invalid" ng-click="vm.suspendAccount()"><i class="fa fa-lock"></i> Suspend</button>
                </div>
            </form>
        </div>
    </div>
</div>
