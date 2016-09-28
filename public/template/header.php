<?php
require_once('../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
?>
<!-- begin #header -->
<div id="header" ng-controller="headerController as vm" class="header navbar navbar-default navbar-fixed-top">
    <!-- begin container-fluid -->
    <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">
            <a ui-sref="app.dashboard" class="navbar-brand" style="width:250px"><span class="navbar-logo"></span> <?=$omsObj->options['app_name']?></a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- end mobile sidebar expand / collapse button -->
        
        <!-- begin header navigation right -->
        <ul class="nav navbar-nav navbar-right">
            <li>
                <form class="navbar-form full-width">
                    <div class="form-group">
                        <input type="text" ng-model="vm.search" ng-change="vm.searchUsers()" class="form-control" placeholder="Enter keyword" />
                        <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="dropdownAutocomplete" ng-show="vm.search">
                        <ul>
                            <li ng-repeat="user in vm.users" ui-sref="app.profile({seo: '{{user.cell[11]}}'})">
                                <img class="comment-photo" src="/api/getUserAvatar/{{user.cell[0]}}"> <span class="comment-name">{{user.cell[1]}}<br /><small>{{user.cell[5]}}</small></span>
                            </li>
                            <li ng-if="vm.users.length == 0">
                                No results found
                            </li>
                        </ul>
                    </div>
                </form>
            </li>
            <li class="dropdown" ng-if="setting.layout.notifications === true">
                <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                    <i class="fa fa-bell-o"></i>
                    <span class="label">5</span>
                </a>
                <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                    <li class="dropdown-header">Notifications (5)</li>
                    <li class="media">
                        <a href="javascript:;">
                            <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                            <div class="media-body">
                                <h6 class="media-heading">Server Error Reports</h6>
                                <div class="text-muted f-s-11">3 minutes ago</div>
                            </div>
                        </a>
                    </li>
                    <li class="media">
                        <a href="javascript:;">
                            <div class="media-left"><img src="assets/img/user-1.jpg" class="media-object" alt="" /></div>
                            <div class="media-body">
                                <h6 class="media-heading">John Smith</h6>
                                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                <div class="text-muted f-s-11">25 minutes ago</div>
                            </div>
                        </a>
                    </li>
                    <li class="media">
                        <a href="javascript:;">
                            <div class="media-left"><img src="assets/img/user-2.jpg" class="media-object" alt="" /></div>
                            <div class="media-body">
                                <h6 class="media-heading">Olivia</h6>
                                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                <div class="text-muted f-s-11">35 minutes ago</div>
                            </div>
                        </a>
                    </li>
                    <li class="media">
                        <a href="javascript:;">
                            <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
                            <div class="media-body">
                                <h6 class="media-heading"> New User Registered</h6>
                                <div class="text-muted f-s-11">1 hour ago</div>
                            </div>
                        </a>
                    </li>
                    <li class="media">
                        <a href="javascript:;">
                            <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
                            <div class="media-body">
                                <h6 class="media-heading"> New Email From John</h6>
                                <div class="text-muted f-s-11">2 hour ago</div>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown-footer text-center">
                        <a href="javascript:;">View more</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown navbar-language" ng-if="setting.layout.pageLanguageBar === true">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="flag-icon flag-icon-us" title="us"></span>
                    <span class="name">EN</span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInRight p-b-0">
                    <li class="arrow"></li>
                    <li><a href="javascript:;"><span class="flag-icon flag-icon-us" title="us"></span> English</a></li>
                    <li><a href="javascript:;"><span class="flag-icon flag-icon-cn" title="cn"></span> Chinese</a></li>
                    <li><a href="javascript:;"><span class="flag-icon flag-icon-jp" title="jp"></span> Japanese</a></li>
                    <li><a href="javascript:;"><span class="flag-icon flag-icon-be" title="be"></span> Belgium</a></li>
                    <li class="divider m-b-0"></li>
                    <li class="text-center"><a href="javascript:;">more options</a></li>
                </ul>
            </li>
            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="/api/getUserAvatar/<?=$omsObj->userData['id']?>" alt="" /> 
                    <span class="hidden-xs"><?=$omsObj->userData['fullname']?></span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <li><a href="javascript:;" ui-sref="app.profile({seo: '<?=$omsObj->userData['seo_url']?>'})">Edit Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:;" ng-click="vm.logout()">Log Out</a></li>
                </ul>
            </li>
            <li class="hidden-xs" ng-if="setting.layout.pageTwoSidebar === true">
                <a href="javascript:;" data-click="right-sidebar-toggled" class="f-s-14">
                    <i class="fa fa-th"></i>
                </a>
            </li>
        </ul>
        <!-- end header navigation right -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end #header -->