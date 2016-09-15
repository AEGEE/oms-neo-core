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
            <div class="alert alert-danger hiddenItem" id="loginError">Username / password invalid!</div>
            <form name="loginForm" method="POST" class="margin-bottom-0" ng-submit="vm.login()" novalidate>
                <div class="form-group m-b-20">
                    <input type="email" class="form-control input-lg" ng-model="vm.user.username" placeholder="Email Address" required/>
                </div>
                <div class="form-group m-b-20">
                    <input type="password" class="form-control input-lg" ng-model="vm.user.password" placeholder="Password" required/>
                </div>
                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg" ng-disabled="loginForm.$pristine || loginForm.$invalid">Sign me in</button>
                </div>
            </form>
        </div>
    </div>
    <!-- end login -->
</div>