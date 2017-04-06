<?php
class omsHelperScript {
	public $userData = "";
	public $options = "";
	public $appVersion = "";
	public $menuMarkUp = "";
	public $moduleAccess = "";
	public $systemRoles = array();

	public function __construct() {
		session_start();
		$this->options = $_SESSION['globals'];
		$this->appVersion = $_SESSION['app_version'];
		$this->menuMarkUp = isset($_SESSION['moduleMarkup']) ? $_SESSION['moduleMarkup'] : "";
    $this->moduleAccess = $_SESSION['moduleAccess'];
    $this->userData = $_SESSION['userData'];
    $this->systemRoles = $_SESSION['systemRoles'];
		session_write_close();
	}

	public function hasWriteAccess($moduleCode) {
		return $this->moduleAccess[$moduleCode] == 1;
	}

	public function hasSystemRole($systemRoleName) {
		if($this->userData['is_superadmin'] == 1 || in_array($systemRoleName, $this->systemRoles)) {
			return true;
		}

		return false;
	}
}
