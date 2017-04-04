<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Recruitment Campaigns</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Recruitment Campaigns</h1>
    <!-- end page-header -->
	
	<div class="row hiddenItem" id="filters">
        <div class="panel panel-inverse" data-sortable-id="ui-general-1">
            <div class="panel-heading">
                <button type="button" class="close" aria-hidden="true" ng-click="vm.toggleFilters()">×</button>
                <h4 class="panel-title">Filter</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4" ng-show="vm.is_superadmin">
                        <div class="form-group">
                            <label for="fBody">Body name</label>
                            <select class="form-control" id="fBody" ng-model="vm.filter.antenna_id" ng-options="antenna.cell[0] as antenna.cell[1] for antenna in vm.antennae.rows track by antenna.cell[0]" required>
                            	<option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fStart">Start date</label>
                            <input type="text" id="fStart" class="form-control datePicker" ng-model="vm.filter.start_date" placeholder="start date"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fEnd">End date</label>
                            <input type="text" id="fEnd" class="form-control datePicker" ng-model="vm.filter.end_date" placeholder="end date"/>
                        </div>
                    </div>
                </div>
                <div class="row pull-right">
                    <div class="col-md-12">
                        <button class="btn btn-success" ng-click="vm.searchGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-primary" data-target="#campaignModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Campaign</button>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-success" ng-click="vm.toggleFilters()"><i class="fa fa-search"></i> Toggle filters</button>
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

<div class="modal fade" id="campaignModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Campaign add/edit</h4>
            </div>
            <form name="campaignForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <div class="form-group m-b-20">
                        <label for="start_date">Start date</label>
                        <input type="text" id="start_date" class="form-control datePicker" ng-model="vm.campaign.start_date" placeholder="start date" required/>
                    </div>
                    <div class="form-group m-b-20">
                        <label for="end_date">End date</label>
                        <input type="text" id="end_date" class="form-control datePicker" ng-model="vm.campaign.end_date" placeholder="end date" required/>
                    </div>
                    <div class="form-group m-b-20">
                        <label for="link">Link (if left empty, a random one will be generated)</label>
                        <input type="text" id="link" class="form-control" ng-model="vm.campaign.link" ng-change="vm.checkLinkAvailability()" placeholder="link" required/>
                        <div id="searching" class="alert alert-info hiddenItem"><img src="assets/img/loading.gif" style="width: 20px" /> Checking link availability...</div>
                        <div id="isAvailable" class="alert alert-success hiddenItem">Link is available for use</div>
                        <div id="isTaken" class="alert alert-danger hiddenItem">Link is NOT available for use</div>
                    </div>
                    <div class="form-group m-b-20" ng-show="vm.is_superadmin">
                        <label for="antenna">Body</label>
                        <select class="form-control" id="antenna" ng-model="vm.campaign.antenna_id" ng-options="antenna.cell[0] as antenna.cell[1] for antenna in vm.antennae.rows track by antenna.cell[0]" required>
                        	<option></option>
                        </select>
                    </div>
                    <hr />
                    <h4>Custom fields</h4>
                    <div class="row">
                    	<div class="col-md-6">
                    		<button class="btn btn-success" ng-click="vm.addCustomField()"><i class="fa fa-plus"></i> Add custom field</button>
                    	</div>
                    	<div class="col-md-6">
                    		<button class="btn btn-danger" ng-click="vm.removeLastField()">Remove last field</button>
                    	</div>
                    </div>
                    <hr />
                    <div class="row" ng-repeat="customField in vm.campaign.customFields">
                    	<div class="col-md-6">
	                    	<div class="form-group m-b-20">
		                        <label for="field_name">Field name</label>
		                        <input type="text" id="field_name" class="form-control" ng-model="customField.name" placeholder="field name" required/>
		                    </div>
                    	</div>
                    	<div class="col-md-6">
	                    	<div class="form-group m-b-20">
		                        <label for="field_type">Field type</label>
		                        <select class="form-control" id="field_type" ng-model="customField.type" ng-options="type.id as type.name for type in vm.fieldTypes track by type.id" required>
		                        	<option></option>
		                        </select>
		                    </div>
                    	</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                    <button ng-disabled="antennaForm.$pristine || antennaForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveCampaign()"><i class="fa fa-save"></i> Save campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>