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

$ar_permission=array();
$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_group_permission: WHERE group_id=:group_id:');
$QloadData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
$QloadData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
$QloadData->execute();
if ($QloadData->numberOfRows()>0) {
	while ($QloadData->next()>0) {
		$ar_permission[$QloadData->Value('permission_page')][$QloadData->Value('permission_flag')]=1;
	}
}
$this->setEditElementStorage($ddm_group, $element, $ar_permission);

if (osW_Settings::getInstance()->getAction()=='doedit') {
	$ar_permission=array();
	foreach (osW_VIS2_Navigation::getInstance()->getNavigationRealUnsorted($_POST['tool_id']) as $navigation_element) {
		if (count($navigation_element['permission'])>0) {
			foreach($navigation_element['permission'] as $flag) {
				if ((isset($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]))&&($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]==1)) {
					$ar_permission[$navigation_element['page_name_intern']][$flag]=1;
				} else {
					$ar_permission[$navigation_element['page_name_intern']][$flag]=0;
					if (!isset($_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag])) {
						$_POST['page_'.$navigation_element['page_name_intern'].'_'.$flag]=0;
					}
				}
			}
		}
	}
	$this->setDoEditElementStorage($ddm_group, $element, $ar_permission);
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>