
<!-- begin #header -->
<div id="header" ng-controller="headerController as vm" class="header navbar navbar-default navbar-fixed-top">
    <!-- begin container-fluid -->
    <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">
            <a ui-sref="app.dashboard" class="navbar-brand" style="width:250px">
                <img src="assets/img/logo.png" alt="logo" style="height: 30px; float: left; padding-right: 5px;" />
                    OMS
            </a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- end mobile sidebar expand / collapse button -->

        <!-- begin header navigation right -->
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="javascript:;" ng-click="vm.markRead()" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                    <i class="fa fa-bell-o"></i>
                    <span ng-show="vm.unreadCount > 0" class="label">{{vm.unreadCount}}</span>
                </a>
                <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                    <li class="dropdown-header">Notifications</li>
                    <li class="media" ng-repeat="notification in vm.notifications">
                        <a href="{{ notification.heading_url }}" target="_blank" ng-if="notification.heading_url && !notification.heading_link" ng-class="{'unreadNotification' : !notification.read}">
                            <div class="media-body">
                                <h6 class="media-heading">{{notification.heading}}</h6>
                                <div class="text-muted f-s-11">{{notification.body}}</div>
                            </div>
                        </a>
                        <a href="javascript:;" ng-click="vm.goToLink(notification)" ng-if="!(notification.heading_url && !notification.heading_link)" ng-class="{'unreadNotification' : !notification.read}">
                            <div class="media-body">
                                <h6 class="media-heading">{{notification.heading}}</h6>
                                <p>{{ notification.body}}</p>
                                <div class="text-muted f-s-11">{{notification.time | timeAgo}}</div>
                            </div>
                        </a>

                    </li>
                    <li class="media" ng-show="!vm.notifications.length">
                        <a href="javascript:;">
                            <div class="media-body">
                                <h6 class="media-heading">No notifications</h6>
                            </div>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="/assets/img/user-5.jpg" alt="" />
                    <span class="hidden-xs">{{ vm.user.first_name }} {{ vm.user.last_name }}</span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <li><a href="javascript:;" ui-sref="app.profile">My Profile</a></li>
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
