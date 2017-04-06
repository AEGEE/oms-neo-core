<?php
require_once('../../scripts/template_scripts.php');
$omsObj = new omsHelperScript();
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
	&copy; <?=date('Y')?> <?=$omsObj->options['app_copyright']?> - Version <?=$omsObj->appVersion?><br />
	Powered by <a target="_blank" href="https://github.com/AEGEE/oms-neo-core">Online membership system</a>
</div>
<!-- end #footer -->

<!-- begin scroll to top btn -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
<!-- end scroll to top btn -->
