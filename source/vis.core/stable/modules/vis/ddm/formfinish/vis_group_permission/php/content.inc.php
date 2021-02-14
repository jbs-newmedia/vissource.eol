<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
 * Link: http://oswframe.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

$element='group_permission';

$ar_permission=array();
$ar_tool=$this->getFormDataElement($ddm_group, 'ar_tool');

$navigation=osW_VIS_Navigation::getInstance()->getNavigation($ar_tool['tool_id']);
foreach ($navigation as $navigation_group_id => $navigation_group_details) {
	if (count($navigation_group_details['pages'])>0) {
		foreach ($navigation_group_details['pages'] as $navigation_page_id => $navigation_page_details) {
			if (count($navigation_page_details['navigation_permissionflags'])>0) {
				foreach ($navigation_page_details['navigation_permissionflags'] as $flag) {
					if ((isset($_POST[$navigation_page_details['navigation_page'].'_'.$flag]))&&($_POST[$navigation_page_details['navigation_page'].'_'.$flag]==1)) {
						$ar_permission[$navigation_page_details['navigation_page']][$flag]=1;
					} else {
						$ar_permission[$navigation_page_details['navigation_page']][$flag]=0;
					}
				}
				if (count($navigation_page_details['pages'])>0) {
					foreach ($navigation_page_details['pages'] as $navigation_subpage_id => $navigation_subpage_details) {
						if (count($navigation_subpage_details['navigation_permissionflags'])>0) {
							foreach ($navigation_subpage_details['navigation_permissionflags'] as $flag) {
								if ((isset($_POST[$navigation_subpage_details['navigation_page'].'_'.$flag]))&&($_POST[$navigation_subpage_details['navigation_page'].'_'.$flag]==1)) {
									$ar_permission[$navigation_subpage_details['navigation_page']][$flag]=1;
								} else {
									$ar_permission[$navigation_subpage_details['navigation_page']][$flag]=0;
								}
							}
						}
					}
				}
			}
		}
	}
}

switch (osW_Settings::getInstance()->getAction()) {
	case 'doadd':
		$this->setFormDataElement($ddm_group, $element, $ar_permission);

		foreach($ar_permission as $page => $permission) {
			foreach($permission as $flag => $value) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
				$QdeleteData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
				$QdeleteData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QdeleteData->bindValue(':permission_page:', $page);
				$QdeleteData->bindValue(':permission_flag:', $flag);
				$QdeleteData->execute();
				if ($value==1) {
					$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_group_permission: (group_id, permission_page, permission_flag) VALUES (:group_id:, :permission_page:, :permission_flag:)');
					$QinsertData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
					$QinsertData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
					$QinsertData->bindValue(':permission_page:', $page);
					$QinsertData->bindValue(':permission_flag:', $flag);
					$QinsertData->execute();
				}
			}
		}
		break;
	case 'doedit':
		$this->setFormDataElement($ddm_group, $element, $ar_permission);

		foreach($ar_permission as $page => $permission) {
			foreach($permission as $flag => $value) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
				$QdeleteData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
				$QdeleteData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QdeleteData->bindValue(':permission_page:', $page);
				$QdeleteData->bindValue(':permission_flag:', $flag);
				$QdeleteData->execute();
				if ($value==1) {
					$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_group_permission: (group_id, permission_page, permission_flag) VALUES (:group_id:, :permission_page:, :permission_flag:)');
					$QinsertData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
					$QinsertData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
					$QinsertData->bindValue(':permission_page:', $page);
					$QinsertData->bindValue(':permission_flag:', $flag);
					$QinsertData->execute();
				}
			}
		}
		break;
	case 'dodelete':
		$this->setFormDataElement($ddm_group, $element, $ar_permission);

		foreach($ar_permission as $page => $permission) {
			foreach($permission as $flag => $value) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
				$QdeleteData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
				$QdeleteData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QdeleteData->bindValue(':permission_page:', $page);
				$QdeleteData->bindValue(':permission_flag:', $flag);
				$QdeleteData->execute();
			}
		}
		break;
}

?>