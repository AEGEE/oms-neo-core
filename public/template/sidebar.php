<?php
session_start();
$userData = $_SESSION['userData'];
$menuMarkup = $_SESSION['moduleMarkup'];
session_write_close();
?>
<!-- begin #sidebar -->
<div id="sidebar" class="sidebar" ng-controller="sidebarController" ng-class="{ 'sidebar-transparent': setting.layout.pageSidebarTransparent }">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img src="assets/img/user-13.jpg" alt="" /></a>
                </div>
                <div class="info">
                    <?=$userData['fullname']?>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header">Navigation</li>
            <li ui-sref-active="active"><a ui-sref="app.dashboard"><i class="fa fa-laptop"></i> <span>Dashboard</span></a></li>
            <?=$menuMarkup?>
            <!-- begin sidebar minify button -->
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->

