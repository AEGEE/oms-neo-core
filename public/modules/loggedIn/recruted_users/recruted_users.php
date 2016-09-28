<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Recruted users</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Recruted users</h1>
    <!-- end page-header -->
	<div class="row hiddenItem" id="filters">
        <div class="panel panel-inverse" data-sortable-id="ui-general-1">
            <div class="panel-heading">
                <button type="button" class="close" aria-hidden="true" ng-click="vm.toggleFilters()">×</button>
                <h4 class="panel-title">Filter</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                	<div class="col-md-2">
                        <div class="form-group">
                            <label for="fName">Name</label>
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="User name" />
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                            <label for="fContactEmail">Email</label>
                            <input type="text" id="fContactEmail" class="form-control" ng-model="vm.filter.contact_email" placeholder="Registration email" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fStatus">Status</label>
                            <select class="form-control" id="fStatus" ng-model="vm.filter.status" ng-options="status.id as status.name for status in vm.statuses track by status.id">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" ng-show="vm.is_superadmin">
                        <div class="form-group">
                            <label for="fAntenna">Antenna</label>
                            <select class="form-control" id="fAntenna" ng-model="vm.filter.antenna_id" ng-options="antenna.cell[0] as antenna.cell[1] for antenna in vm.antennae.rows track by antenna.cell[0]" required>
                            	<option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fCampaign">Campaign</label>
                            <select class="form-control" id="fCampaign" ng-model="vm.filter.campaign" ng-options="campaign.cell[0] as campaign.cell[4]+' ('+campaign.cell[1]+')' for campaign in vm.campaigns.rows track by campaign.cell[0]" required>
                            	<option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 p-t-20">
                        <button class="btn btn-success" ng-click="vm.searchGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
       	<div class="col-md-12 text-right">
            <button class="btn btn-success" ng-click="vm.toggleFilters()"><i class="fa fa-search"></i> Toggle filters</button>
            <button class="btn btn-warning" ng-click="vm.exportGrid()"><i class="fa fa-file-excel-o"></i> Export to XLS</button>
        </div>
    </div>
    <div class="row">
        &nbsp;
    </div>
	<div class="row">
		<div class="col-md-12">
			<table id="gridContainer"></table>
			<div id="gridPager"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="userDetailsModal">
    <div class="modal-dialog bigModal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Details for user {{vm.currentUser.first_name}} {{vm.currentUser.last_name}}</h4>
                <h5>Status <span ng-bind-html="status"></span></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Change status</h4>
                        <div class="btn-group">
                            <button ng-repeat="(key, transitions) in vm.currentUser.status_transitions" ng-click="vm.changeStatus(key)" class="btn btn-{{transitions.labelType}}">{{transitions.statusText}}</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">     
                        <h4>Basic details</h4>
                        <table class="table">
                            <tr>
                                <th>Full name</th>
                                <td>{{vm.currentUser.first_name}} {{vm.currentUser.last_name}}</td>
                            </tr>
                            <tr>
                                <th>Date of birth</th>
                                <td>{{vm.currentUser.date_of_birth}}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{vm.currentUser.gender}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{vm.currentUser.email}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{vm.currentUser.phone}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{vm.currentUser.address}}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{vm.currentUser.city}}</td>
                            </tr>
                            <tr>
                                <th>Zip code</th>
                                <td>{{vm.currentUser.zipcode}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Professional details</h4>
                        <table class="table">
                            <tr>
                                <th>University</th>
                                <td>{{vm.currentUser.university}}</td>
                            </tr>
                            <tr>
                                <th>Studies type</th>
                                <td>{{vm.currentUser.study_type}}</td>
                            </tr>
                            <tr>
                                <th>Studies field</th>
                                <td>{{vm.currentUser.study_field}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr />
                <div class="row" ng-show="vm.currentUser.custom_responses.length > 0">
                    <div class="col-md-12">
                        <h4>Responses</h4>
                        <table class="table">
                            <tr ng-repeat="response in vm.currentUser.custom_responses">
                                <th>{{response.name}}</th>
                                <td>{{response.value}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Recruter comments</h4>
                        <div class="row" ng-repeat="comment in vm.currentUser.comments">
                            <hr />
                            <div class="col-md-12">
                                <div class="comment">
                                    <img class="img-thumbnail comment-photo" src="/api/getUserAvatar/{{comment.user_id}}"> <span class="comment-name"><b>{{comment.commenter_name}}</b> ({{comment.created_at}})</span>
                                </div>
                            </div>
                            <div class="col-md-12 comment-text" btf-markdown="comment.comment">
                                
                            </div>
                        </div>
                        <hr />
                        <div id="commentArea" class="row hiddenItem">
                            <div class="col-md-12 p-b-10">
                                <label for="newComment">New comment</label>
                                <textarea id="newComment" ng-model="vm.comment" class="form-control commentField"></textarea>
                                <div id="previewComment" class="well hiddenItem" btf-markdown="vm.comment"></div>
                            </div>
                            <div class="col-md-12 text-right">
                                <i>Comments support markdown syntax <a target="_blank" href="https://guides.github.com/features/mastering-markdown/">Help here</a></i><br /><br />
                                <button ng-click="vm.previewComment()" class="btn btn-warning"><i class="fa fa-search"></i> {{vm.what}} comment</button>
                                <button ng-click="vm.addComment()" class="btn btn-success"><i class="fa fa-save"></i> Save comment</button>
                            </div>
                        </div>
                        <div id="addCommBtn" class="row">
                            <div class="col-md-12">
                                <button ng-click="vm.showCommentArea()" class="btn btn-success"><i class="fa fa-comment"></i> Add comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activateUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Activating user {{vm.currentUser.first_name}} {{vm.currentUser.last_name}}</h4>
            </div>
            <form name="activateForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <h4>Basic details</h4>
                    <div class="form-group m-b-20">
                        <label for="activateDepartment">Department</label>
                        <select class="form-control" id="activateDepartment" ng-model="vm.currentUser.department_id" ng-options="department.cell[0] as department.cell[1] for department in vm.departments.rows track by department.cell[0]">
                            <option></option>
                        </select>
                    </div>
                    <hr />
                    <div class="row">
                        
                        <h4>Roles</h4>
                        <div class="form-group m-b-20" ng-repeat="role in vm.roles">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="vm.currentUser.roles[role.cell[0]]" value="{{role.cell[0]}}"> {{role.cell[1]}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
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
                                            <input type="checkbox" ng-model="vm.currentUser.fees[fee.cell[0]]" value="{{fee.cell[0]}}"> {{fee.cell[1]}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="vm.currentUser.fees[fee.cell[0]]">
                                    <label>Paid on</label>
                                    <input id="feepaid_{{fee.cell[0]}}" class="form-control paidOnDateFee" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" ng-click="vm.closeAndResetActivate()"><i class="fa fa-ban"></i> Close</button>
                    <button ng-disabled="activateForm.$pristine || activateForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.createAndActivateUser()"><i class="fa fa-save"></i> Activate user</button>
                </div>
            </form>
        </div>
    </div>
</div>
