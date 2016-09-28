<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Roles</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Roles</h1>
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
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Role name" required/>
                        </div>
                    </div>
                    <div class="col-md-8 text-right p-t-20">
                        <button class="btn btn-success" ng-click="vm.searchRolesGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php
        if($omsObj->hasWriteAccess('roles')) { ?>
            <div class="col-md-6">
                <button class="btn btn-primary" data-target="#roleModal" data-toggle="modal"><i class="fa fa-plus"></i> Add role</button>
            </div>
        <?php } ?>
        <div class="col-md-6 text-right">
            <button class="btn btn-success" ng-click="vm.toggleFilters()"><i class="fa fa-search"></i> Toggle filters</button>
        </div>
    </div>
    <div class="row">
        &nbsp;
    </div>
	<div class="row">
		<div class="col-md-12">
			<table id="roleGrid"></table>
			<div id="rolePager"></div>
		</div>
	</div>
</div>

<?php
if($omsObj->hasWriteAccess('roles')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="roleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Role add/edit</h4>
                </div>
                <form name="roleForm" method="POST" class="margin-bottom-0" novalidate>
                    <div class="modal-body">
                        <div class="form-group m-b-20">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" ng-model="vm.role.name" placeholder="Role name" required/>
                        </div>
                        <h4>Access to pages</h4>
                        <div class="form-group m-b-20" ng-repeat="modules in vm.modules.rows">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" ng-model="vm.role.module[modules.cell[0]]" value="{{modules.cell[0]}}"> {{modules.cell[1]}} ({{modules.cell[4]}})
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" ng-show="vm.role.module[modules.cell[0]]">
                                    <label>Access level</label>
                                    <select class="form-control" ng-model="vm.role.moduleAccess[modules.cell[0]]" ng-options="item.value as item.name for item in vm.accessOptions"></select>
                                </div>
                            </div>
                            <hr />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                        <button ng-disabled="roleForm.$pristine || roleForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveRole()"><i class="fa fa-save"></i> Save role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>