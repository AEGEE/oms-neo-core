<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Antennae management</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Antennae management</h1>
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
                            <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Antenna name" required/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fCity">City</label>
                            <input type="text" id="fCity" class="form-control" ng-model="vm.filter.city" placeholder="Antenna city" required/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fCountry">Country</label>
                            <select ng-model="vm.filter.country" class="form-control" id="fCountry">
                                <option value=""></option>
                                <option ng-repeat="(key, value) in vm.countries" value="{{key}}">{{value}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row pull-right">
                    <div class="col-md-12">
                        <button class="btn btn-success" ng-click="vm.searchAntennaGrid()"><i class="fa fa-search"></i> Search</button>
                        <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php
            if($omsObj->hasWriteAccess('antennae_management')) { ?>
                <button class="btn btn-primary" data-target="#antennaModal" data-toggle="modal"><i class="fa fa-plus"></i> Add Antenna</button>
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
			<table id="antennaeGrid"></table>
			<div id="antennaePager"></div>
		</div>
	</div>
</div>

<?php
if($omsObj->hasWriteAccess('antennae_management')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="antennaModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Antenna add/edit</h4>
                </div>
                <form name="antennaForm" method="POST" class="margin-bottom-0" novalidate>
                    <div class="modal-body">
                        <div class="form-group m-b-20">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" ng-model="vm.antenna.name" placeholder="Antenna name" required/>
                        </div>
                        <div class="form-group m-b-20">
                            <label for="city">City</label>
                            <input type="text" id="city" class="form-control" ng-model="vm.antenna.city" placeholder="Antenna city" required/>
                        </div>
                        <div class="form-group m-b-20">
                            <label for="countries">Country</label>
                            <select ng-model="vm.antenna.country" class="form-control" id="countries">
                                <option value=""></option>
                                <option ng-repeat="(key, value) in vm.countries" value="{{key}}">{{value}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                        <button ng-disabled="antennaForm.$pristine || antennaForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveAntenna()"><i class="fa fa-save"></i> Save antenna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>