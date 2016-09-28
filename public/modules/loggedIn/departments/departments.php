<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Departments</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Departments</h1>
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
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Department name" required/>
                        </div>
                    </div>
                    <div class="col-md-8 text-right p-t-20">
                        <button class="btn btn-success" ng-click="vm.searchDepartmentGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php
            if($omsObj->hasWriteAccess('departments')) { ?>
                <button class="btn btn-primary" data-target="#departmentModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Department</button>
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
			<table id="departmentGrid"></table>
			<div id="departmentPager"></div>
		</div>
	</div>
</div>


<?php
if($omsObj->hasWriteAccess('departments')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="departmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Department add/edit</h4>
                </div>
                <form name="workGroupForm" method="POST" class="margin-bottom-0" novalidate>
                    <div class="modal-body">
                        <div class="form-group m-b-20">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" ng-model="vm.department.name" placeholder="Department name" required/>
                        </div>
                        <div class="form-group m-b-20">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control" ng-model="vm.department.description" placeholder="Department description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                        <button ng-disabled="workGroupForm.$pristine || workGroupForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveDepartment()"><i class="fa fa-save"></i> Save department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>