<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a ui-sref="app.dashboard">Dashboard</a></li>
        <li class="active">News</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">News</h1>
    <!-- end page-header -->

    <?php 
    if($omsObj->hasSystemRole('announcer')) { ?>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" data-target="#newsModal" data-toggle="modal"><i class="fa fa-plus"></i> Add News</button>
            </div>
        </div>
        <div class="row">
            &nbsp;
        </div>
    <?php } ?>

    <div class="row well m-b-20" ng-repeat="new in vm.latestNews.rows">
        <div class="col-md-12">
            <h4 class="media-heading" ng-click="vm.goToNews(new.id)"><a href="#">{{new.cell[1]}}</a><br /><small>Posted on {{new.cell[3]}} by {{new.cell[4]}}</small></h4>
            <?php 
            if($omsObj->hasSystemRole('announcer')) { ?>
                <div class="p-b-20">
                    <button class="btn btn-xs btn-success" ng-click="vm.editNews(new.id)">Edit</button>
                    <button class="btn btn-xs btn-danger" ng-click="vm.deleteNews(new.id)">Delete</button>
                </div>
            <?php } ?>
            <span btf-markdown="new.cell[2]"></span>
        </div>
    </div>
</div>

<?php 
if($omsObj->hasSystemRole('announcer')) { ?>
    <!-- #modal-dialog -->
    <div class="modal fade" id="newsModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">News add/edit</h4>
                </div>
                <form name="newsForm" method="POST" class="margin-bottom-0" novalidate>
                    <div class="modal-body">
                        <div class="form-group m-b-20">
                            <label for="title">Title</label>
                            <input type="text" id="title" class="form-control" ng-model="vm.news.title" placeholder="Title" required/>
                        </div>
                        <div class="form-group m-b-20">
                            <label for="newsContent">Content</label>
                            <textarea id="newsContent" class="form-control commentField" ng-model="vm.news.content" placeholder="Content" required></textarea>
                            <div id="previewNews" class="well hiddenItem" btf-markdown="vm.news.content"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <i>News support markdown syntax <a target="_blank" href="https://guides.github.com/features/mastering-markdown/">Help here</a></i><br /><br />
                            </div>
                            <div class="col-md-6 text-right">
                                <button ng-click="vm.previewNews()" class="btn btn-warning"><i class="fa fa-search"></i> {{vm.what}} news</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-danger" ng-click="vm.closeAndReset()"><i class="fa fa-ban"></i> Close</button>
                        <button ng-disabled="newsForm.$pristine || newsForm.$invalid" class="btn btn-sm btn-success" ng-click="vm.saveNews()"><i class="fa fa-save"></i> Save news</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
