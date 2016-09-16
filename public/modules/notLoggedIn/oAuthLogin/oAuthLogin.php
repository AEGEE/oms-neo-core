<?php
session_start();
$options = $_SESSION['globals'];
session_write_close();
?>
<div>
    <div class="login-cover">
        <div class="login-cover-image"><img src="assets/img/login-bg/bg-1.jpg" data-id="login-cover-image" alt="" /></div>
        <div class="login-cover-bg"></div>
    </div>
    
    <!-- begin login -->
    <div class="login login-v2" data-pageload-addclass="animated fadeIn">
        <!-- begin brand -->
        <div class="login-header">
            <div class="brand">
                <span class="logo"></span> <?=$options['app_name']?>
                <small>Login</small>
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </div>
        <!-- end brand -->
        <div class="login-content">
            <div class="login-buttons">
                <button type="submit" ng-click="vm.loginOauth()" class="btn btn-success btn-block btn-lg">Login using your <?=$options['app_name']?> ID</button>
            </div>
        </div>
    </div>
    <!-- end login -->
</div>