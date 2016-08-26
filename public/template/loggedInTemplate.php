<?php
session_start();
$options = $_SESSION['globals'];
session_write_close();
?>
<!-- begin #header -->
<div ui-view="header"></div>
<!-- end #header -->

<!-- begin #sidebar -->
<div ui-view="sidebar"></div>
<!-- end #sidebar -->

<!-- begin #content -->
<div id="content" view-content class="content" ng-class="{ 
    'content-full-width': setting.layout.pageContentFullWidth, 
    'height-full': setting.layout.pageContentFullHeight, 
    'content-inverse-mode': setting.layout.pageContentInverseMode 
}">
    <div ui-view="pageContent"></div>
</div>
<!-- end #content -->

<!-- begin #footer -->
<div id="footer" class="footer">
	&copy; <?=date('Y')?> <?=$options['app_copyright']?>
</div>
<!-- end #footer -->

<!-- begin scroll to top btn -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
<!-- end scroll to top btn -->