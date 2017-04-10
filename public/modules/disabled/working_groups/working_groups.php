<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Working Groups</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Working Groups</h1>
    <!-- end page-header -->

    <div class="row hiddenItem" id="filters">
        <div class="panel panel-inverse" data-sortable-id="ui-general-1">
            <div class="panel-heading">
                <button type="button" class="close" aria-hidden="true" ng-click="vm.toggleFilters()">×</button>
                <h4 class="panel-title">Filter</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fName">Name</label>
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Working group name" required/>
                        </div>
                    </div>
                    <div class="col-md-8 text-right p-t-20">
                        <button class="btn btn-success" ng-click="vm.searchWorkGroupGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php
            if($omsObj->hasWriteAccess('working_groups')) { ?>
                <button class="btn btn-primary" data-target="#workGroupModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Working group</button>
            <?php } ?>
        </div>
        <div class="col-md-8 text-right">
            <button class="btn btn-success" ng-click="vm.toggleFilters()"><i class="fa fa-search"></i> Toggle filters</button>
            <button class="btn btn-warning" ng-click="vm.exportGrid()"><i class="fa fa-file-excel-o"></i> Export to XLS</button>
        </div>
    </div>
    <div class="row">
        &nbsp;
    </div>
	<div class="row">
		<div class="col-md-12">
			<table id="workGroupGrid"></table>
			<div id="workGroupPager"></div>
		</div>
	</div>
</div>

<?php
if($omsObj->hasWriteAccess('working_groups')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="workGroupModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Work group add/edit</h4>
                </div>
                <form name="workGroupForm" method="POST" class="margin-bottom-0" novalidate>
                    <div class="modal-body">
                        <div class="form-group m-b-20">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" ng-model="vm.workGroup.name" placeholder="Work group name" required/>
                        </div>
                        <div class="form-group m-b-20">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control" ng-model="vm.workGroup.description" placeholder="Work group description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                        <button ng-disabled="workGroupForm.$pristine || workGroupForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveWorkGroup()"><i class="fa fa-save"></i> Save work group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>