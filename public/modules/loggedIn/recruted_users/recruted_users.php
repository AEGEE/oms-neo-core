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
	<div class="row">
        <div class="panel panel-inverse" data-sortable-id="ui-general-1">
            <div class="panel-heading">
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


