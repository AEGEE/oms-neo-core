<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">Settings</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Settings</h1>
    <!-- end page-header -->
</div>


<ul class="nav nav-tabs">
    <li class="active"><a href="#" data-target="#settings-tab-1" data-toggle="tab">Global settings</a></li>
    <li class=""><a id="emailTab" href="#" data-target="#settings-tab-2" data-toggle="tab">Email templates</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in p-10" id="settings-tab-1">
        <h3 class="m-t-10"><i class="fa fa-cog"></i> Global settings</h3>
        <div class="row">
            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
                <div class="panel-heading">
                    <h4 class="panel-title">Filter</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fName">Name</label>
                                <input type="text" id="fName" class="form-control" ng-model="vm.filter.name" placeholder="Setting name" required/>
                            </div>
                        </div>
                        <div class="col-md-8 text-right p-t-20">
                            <button class="btn btn-success" ng-click="vm.searchOptionsGrid()"><i class="fa fa-search"></i> Search</button>
                            <button class="btn btn-danger" ng-click="vm.clearSearch()"><i class="fa fa-ban"></i> Clear search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="settingGrid"></table>
                <div id="settingPager"></div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade in p-10" id="settings-tab-2">
        <h3 class="m-t-10"><i class="fa fa-envelope-o"></i> Email templates</h3>
        <div class="row">
            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
                <div class="panel-heading">
                    <h4 class="panel-title">Filter</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fEmailName">Name</label>
                                <input type="text" id="fEmailName" class="form-control" ng-model="vm.filterEmail.name" placeholder="Email template name" required/>
                            </div>
                        </div>
                        <div class="col-md-8 text-right p-t-20">
                            <button class="btn btn-success" ng-click="vm.searchEmailsGrid()"><i class="fa fa-search"></i> Search</button>
                            <button class="btn btn-danger" ng-click="vm.clearEmailSearch()"><i class="fa fa-ban"></i> Clear search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="emailGrid"></table>
                <div id="emailPager"></div>
            </div>
        </div>
    </div>
</div>

<?php
if($omsObj->hasWriteAccess('settings')) { ?>
<!-- #modal-dialog -->
<div class="modal fade" id="optionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Setting edit</h4>
            </div>
            <form name="settingForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <h5>Setting name: <b>{{vm.option.name}}</b></h5>
                    <h5>Setting description: <b>{{vm.option.description}}</b></h5>
                    <div class="form-group m-b-20">
                        <label for="value">Value</label>
                        <input type="text" id="value" class="form-control" ng-model="vm.option.value" placeholder="Setting value" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                    <button ng-disabled="settingForm.$pristine || settingForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveOption()"><i class="fa fa-save"></i> Save setting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="emailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Setting edit</h4>
            </div>
            <form name="emailForm" method="POST" class="margin-bottom-0" novalidate>
                <div class="modal-body">
                    <h5>Email template name: <b>{{vm.email.name}}</b></h5>
                    <h5>Email template description: <b>{{vm.email.description}}</b></h5>
                    <div class="form-group m-b-20">
                        <label for="emailTitle">Title</label>
                        <input type="text" id="emailTitle" class="form-control" ng-model="vm.email.title" placeholder="Email title" required/>
                    </div>
                    <div class="form-group m-b-20">
                        <label for="emailContent">Title</label>
                        <textarea id="emailContent" class="form-control" ng-model="vm.email.content" placeholder="Email content"></textarea>
                    </div>
                    <h5>Tags allowed: {{vm.email.allowed_fields}}</h5>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" ng-click="vm.closeAndResetEmail()"><i class="fa fa-ban"></i> Close</button>
                    <button ng-disabled="emailForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveEmail()"><i class="fa fa-save"></i> Save email</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>