<?php
require_once('../../../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<div>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">Dashboard</h1>
    <!-- end page-header -->
    
    <div class="alert alert-danger" ng-show="vm.suspended">
        You account was suspended!<br />
        You only have access to limited features. <br />
        Suspention reason: {{vm.suspendedFor}}
    </div>

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

    <div class="row">
        <div class="col-md-6"> 
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Latest news</h4>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="latest-post">
                        <ul class="media-list media-list-with-divider">
                            <li class="media media-lg" ng-repeat="new in vm.latestNews.rows">
                                <div class="media-body">
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
                            </li>
                        </ul>
                        <div ng-show="!vm.latestNews.rows.length" class="alert alert-danger">
                            No news yet!
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-users fa-fw"></i></div>
                <div class="stats-title">Total members</div>
                <div class="stats-number">{{vm.users}}</div>
            </div>
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Newest members</h4>
                </div>
                <ul class="registered-users-list clearfix">
                    <li ng-repeat="user in vm.newestMembers">
                        <a href="javascript:;" ui-sref="app.profile({seo: user.seo_url})"><img class="dashboardPics" src="/api/getUserAvatar/{{user.id}}" alt="" /></a>
                        <h4 class="username text-ellipsis">
                            {{user.fullname}}
                            <small>{{user.local}}</small>
                        </h4>
                    </li>
                </ul>
            </div>
            <!-- end panel -->
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