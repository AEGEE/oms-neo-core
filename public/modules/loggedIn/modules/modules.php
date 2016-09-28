<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Modules</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Modules</h1>
    <!-- end page-header -->
    <?php
    if($omsObj->hasWriteAccess('modules')) { ?>
        <div class="row">
            <div class="alert alert-danger">
                Installing new modules: <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#newModuleModal">Info</button>
            </div>
        </div>
    <?php } ?>

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
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Module name" required/>
                        </div>
                    </div>
                    <div class="col-md-8 text-right p-t-20">
                        <button class="btn btn-success" ng-click="vm.searchModulesGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-success" ng-click="vm.toggleFilters()"><i class="fa fa-search"></i> Toggle filters</button>
        </div>
    </div>
    <div class="row">
        &nbsp;
    </div>
	<div class="row">
		<div class="col-md-12">
			<table id="moduleGrid"></table>
			<div id="modulePager"></div>
		</div>
	</div>
</div>

<?php
if($omsObj->hasWriteAccess('modules')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="newModuleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Installing new modules</h4>
                </div>
                <div class="modal-body">   
                    To install new modules you will need this key:<br />
                    <button class="btn btn-success" id="revealKeyBtn" ng-click="vm.getSharedSecret()">Reveal key</button>
                    <div class="hiddenItem" id="keyContainer">
                        <code id="sharedSecret"></code>
                        <br />
                        <hr />
                        If you think this secret has been compromised, you can regenerate the a new key. <br /><b>This will not affect existing modules!</b><br />
                        <button class="btn btn-danger" ng-click="vm.generateNewSharedSecret()">Regenerate key</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>