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
    <li class="active"><a href="#" data-target="#settings-tab-1" data-toggle="tab"><i class="fa fa-cog"></i> Global settings</a></li>
    <li class=""><a id="emailTab" href="#" data-target="#settings-tab-2" data-toggle="tab"><i class="fa fa-envelope-o"></i> Email templates</a></li>
    <li class=""><a id="emailTab" href="#" data-target="#settings-tab-3" data-toggle="tab"><i class="fa fa-align-left"></i> Navigation menu</a></li>
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
    <div class="tab-pane fade in p-10" id="settings-tab-3">
        <h3 class="m-t-10"><i class="fa fa-align-left"></i> Navigation menu</h3>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i> An item with children will have its link removed!
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="dd nestable" id="nestable">
                    <ol class="dd-list">
                        <li class="dd-item" ng-repeat="item in vm.menuItems" ng-if="!item.deleted && !item.is_child" data-id="{{item.id}}" data-name="{{item.name}}" data-type="{{item.type}}" data-link="{{item.link}}" data-page="{{item.page}}" data-deleted={{item.deleted}} data-icon="{{item.icon}}" >
                            <div class="dd-handle"><i class="fa {{item.icon}}"></i> {{item.name}} <span ng-show="item.link || item.page">({{item.link}}{{vm.modules[item.page]}})</span><br />(Type {{vm.itemTypes[item.type]}})</div>
                            <span class="button-edit btn btn-default btn-xs pull-right" ng-click="vm.editItem(item.internal_id)">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </span>
                            <span class="button-delete btn btn-default btn-xs pull-right" ng-click="vm.deleteItem(item.internal_id)">
                                <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            </span>


                            <!-- Item3 children -->
                            <ol class="dd-list" ng-if="item.children">
                                <!-- Item4 -->
                                 <li class="dd-item" ng-repeat="child in item.children" ng-if="!child.deleted" data-id="{{child.id}}" data-name="{{child.name}}" data-type="{{child.type}}" data-link="{{child.link}}" data-page="{{child.page}}" data-deleted={{child.deleted}} data-icon="{{child.icon}}" >
                                    <div class="dd-handle"><i class="fa {{child.icon}}"></i> {{child.name}} <span ng-show="child.link || child.page">({{child.link}}{{vm.modules[child.page]}})</span><br />(Type {{vm.itemTypes[child.type]}})</div>
                                    <span class="button-edit btn btn-default btn-xs pull-right" ng-click="vm.editItem(child.internal_id)">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </span>
                                    <span class="button-delete btn btn-default btn-xs pull-right" ng-click="vm.deleteItem(child.internal_id)">
                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                    </span>
                                </li>
                            </ol>


                        </li>
                    </ol>
                </div>
            </div>
            <div class="col-md-6">
                <h5 ng-if="!vm.item.internal_id">Add item</h5>
                <h5 ng-if="vm.item.internal_id">Edit item</h5>
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Icon</label>
                        <select class="form-control" ng-model="vm.item.icon" style="font-family: 'FontAwesome'" ng-options="icon.name as icon.code+' '+icon.name for icon in vm.faIcons">

                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="menuItemName" class="form-control" ng-model="vm.item.name" placeholder="Menu name" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" ng-model="vm.item.type">
                                <option value="0">No link</option>
                                <option value="1">External Link</option>
                                <option value="2">Module page link</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" ng-show="vm.item.type == 2">
                        <div class="form-group m-b-20">
                            <label for="page">Module page</label>
                            <select class="form-control" ng-model="vm.item.page" ng-options="key as value for (key, value) in vm.modules">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" ng-show="vm.item.type == 1">
                        <div class="form-group m-b-20">
                            <label for="link">Link</label>
                            <input type="text" id="link" class="form-control" ng-model="vm.item.link" placeholder="Menu link" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success" ng-if="!vm.item.internal_id" ng-click="vm.addMenuItem()"><i class="fa fa-plus"></i> Add menu item</button>
                        <button class="btn btn-success" ng-if="vm.item.internal_id" ng-click="vm.saveMenuItem()"><i class="fa fa-plus"></i> Save menu item</button>
                    </div>
                    
                </div>
            </div>
        </div>
        <hr />
        <button class="btn btn-success" ng-click="vm.saveMenu()"><i class="fa fa-plus"></i> Save menu</button>




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