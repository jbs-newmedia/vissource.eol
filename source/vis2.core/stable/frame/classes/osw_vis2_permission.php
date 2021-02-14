<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame.VIS2 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

class osW_VIS2_Permission extends osW_Object {

	/*** VALUES ***/

	private $data=array();

	/*** METHODS CORE ***/

	public function __construct() {
		parent::__construct(1, 1);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/*** METHODS ***/

	public function addPermissionFlag($navigation_id, $navigation_permissionflags, $tool_id) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permission_'.$tool_id;

		if (!isset($this->data[$name])) {
			$this->data[$name]=array();
		}

		if (count($navigation_permissionflags)>0) {
			$this->data[$name][$navigation_id]=$navigation_permissionflags;
		}
		return true;
	}

	public function getPermissionFlags($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permission_'.$tool_id;

		return $this->data[$name];
	}

	public function loadPermissionText($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permissiontext_'.$tool_id;

		if (!isset($this->data[$name])) {
			$Qselect=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_permission: WHERE tool_id=:tool_id: AND permission_ispublic=:permission_ispublic:');
			$Qselect->bindTable(':table_vis2_permission:', 'vis2_permission');
			$Qselect->bindInt(':tool_id:', $tool_id);
			$Qselect->bindInt(':permission_ispublic:', 1);
			$Qselect->execute();
			if ($Qselect->numberOfRows()>0) {
				while($Qselect->next()) {
					$this->data[$name][$Qselect->result['permission_flag']]=$Qselect->result['permission_title'];
				}
			}
		}

		return true;
	}

	public function getPermissionTextList($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permissiontext_'.$tool_id;

		if (!isset($this->data[$name])) {
			$this->loadPermissionText($tool_id);
		}

		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		return '';
	}

	public function loadPermissionTextById($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permissiontextbyid_'.$tool_id;

		if (!isset($this->data[$name])) {
			$Qselect=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_permission: WHERE tool_id=:tool_id: AND permission_ispublic=:permission_ispublic:');
			$Qselect->bindTable(':table_vis2_permission:', 'vis2_permission');
			$Qselect->bindInt(':tool_id:', $tool_id);
			$Qselect->bindInt(':permission_ispublic:', 1);
			$Qselect->execute();
			if ($Qselect->numberOfRows()>0) {
				while($Qselect->next()) {
					$this->data[$name][$Qselect->result['permission_id']]=$Qselect->result['permission_title'];
				}
			}
		}

		return true;
	}

	public function getPermissionTextListById($tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permissiontextbyid_'.$tool_id;

		if (!isset($this->data[$name])) {
			$this->loadPermissionTextById($tool_id);
		}

		return $this->data[$name];
	}

	public function getPermissionText($permission_flag, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}

		$name='permissiontext_'.$tool_id;

		if (!isset($this->data[$name])) {
			$this->loadPermissionText($tool_id);
		}

		if (isset($this->data[$name][$permission_flag])) {
			return $this->data[$name][$permission_flag];
		}

		return 'unset';
	}

	public function loadPermissionsByUserId($user_id=0, $tool_id=0) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}
		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getID();
		}
		if (isset($this->data['userpermission'][$user_id])) {
			die('ende');
			return $this->data['userpermission'][$user_id];
		}
		$this->data['userpermission'][$user_id]=array();
		foreach (osW_VIS2_Group::getInstance()->getGroupsByUserId($user_id) as $group_id) {
			$QloadData = osW_Database::getInstance()->query('SELECT * FROM :table_vis2_group_permission: AS gp INNER JOIN :table_vis2_page: AS p ON (p.page_name_intern=gp.permission_page) INNER JOIN :table_vis2_page_permission: AS pp ON (pp.permission_flag=gp.permission_flag AND pp.page_id=p.page_id) WHERE gp.group_id=:group_id:');
			$QloadData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
			$QloadData->bindTable(':table_vis2_page:', 'vis2_page');
			$QloadData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
			$QloadData->bindInt(':group_id:', $group_id);
			$QloadData->execute();
			if ($QloadData->query_handler===false) {
				$this->__initDB();
				$QloadData->execute();
			}
			if ($QloadData->numberOfRows()>0) {
				while ($QloadData->next()) {
					$this->data['userpermission'][$user_id][$QloadData->Value('permission_page')][$QloadData->Value('permission_flag')]=true;
				}
				$this->data['userpermission'][$user_id]['vis_dashboard']['view']=true;
				$this->data['userpermission'][$user_id]['vis_dashboard']['link']=true;
				$this->data['userpermission'][$user_id]['vis_profile']['view']=true;
				$this->data['userpermission'][$user_id]['vis_profile']['link']=true;
				$this->data['userpermission'][$user_id]['vis_settings']['view']=true;
				$this->data['userpermission'][$user_id]['vis_settings']['link']=true;
				$this->data['userpermission'][$user_id]['vis_logout']['view']=true;
				$this->data['userpermission'][$user_id]['vis_logout']['link']=true;
			}
		}
		return $this->data['userpermission'][$user_id];
	}

	public function addPermissionByUserId($permission_page, $permission_flag, $user_id=0, $tool_id=0, $status=true) {
		if ($tool_id==0) {
			$tool_id=osW_VIS2::getInstance()->getToolId();
		}
		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getID();
		}

		$this->data['userpermission'][$user_id][$permission_page][$permission_flag]=$status;
	}

	public function checkPermission($flag, $page='', $user_id=0, $tool_id=0) {
		if ($page=='') {
			$page=osW_VIS2_Navigation::getInstance()->getPage();
		}
		if ($user_id==0) {
			$user_id=osW_VIS2_User::getInstance()->getID();
		}

		if (!isset($this->data['userpermission'][$user_id])) {
			$this->loadPermissionsByUserId($user_id, $tool_id);
		}

		if ((isset($this->data['userpermission'][$user_id][$page][$flag]))&&($this->data['userpermission'][$user_id][$page][$flag]===true)) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @return osW_VIS2_Permission
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>