<?php

/*
 * Author: Juergen Schwind
 * Copyright: Juergen Schwind
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

$ar_navigation_permission=array();
$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id:');
$QloadData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
$QloadData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
$QloadData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolId());
$QloadData->execute();
if ($QloadData->numberOfRows()>0) {
	while ($QloadData->next()>0) {
		$ar_navigation_permission[$QloadData->Value('permission_flag')]=1;
	}
}
$this->setEditElementStorage($ddm_group, $element, $ar_navigation_permission);

if (osW_Settings::getInstance()->getAction()=='doedit') {
	$ar_navigation_permission=array();
	foreach(osW_VIS2_Permission::getInstance()->getPermissionTextList(osW_VIS2::getInstance()->getToolId()) as $permission_flag => $permission_name) {
		if ((isset($_POST[$element.'_'.$permission_flag]))&&($_POST[$element.'_'.$permission_flag]==1)) {
			$ar_navigation_permission[$permission_flag]=1;
		} else {
			$ar_navigation_permission[$permission_flag]=0;
			if (!isset($_POST[$element.'_'.$permission_flag])) {
				$_POST[$element.'_'.$permission_flag]=0;
			}
		}
	}
	$this->setDoEditElementStorage($ddm_group, $element, $ar_navigation_permission);
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>