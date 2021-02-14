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

$ar_user_group=array();
$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id:');
$QloadData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
$QloadData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
$QloadData->execute();
if ($QloadData->numberOfRows()>0) {
	while ($QloadData->next()>0) {
		$ar_user_group[$QloadData->Value('tool_id')][$QloadData->Value('group_id')]=1;
	}
}

$this->setEditElementStorage($ddm_group, $element, $ar_user_group);

if (osW_Settings::getInstance()->getAction()=='doedit') {
	$ar_user_group=array();
	foreach(osW_VIS2_Manager::getInstance()->getTools() as $tool_id => $tool_name) {
		foreach(osW_VIS2_Manager::getInstance()->getGroups($tool_id) as $group_id => $group_name_intern) {
			if ((isset($_POST[$element.'_'.$tool_id.'_'.$group_id]))&&($_POST[$element.'_'.$tool_id.'_'.$group_id]==1)) {
				$ar_user_group[$tool_id][$group_id]=1;
			} else {
				$ar_user_group[$tool_id][$group_id]=0;
				if (!isset($_POST[$element.'_'.$tool_id.'_'.$group_id])) {
					$_POST[$element.'_'.$tool_id.'_'.$group_id]=0;
				}
			}
		}
	}
	$this->setDoEditElementStorage($ddm_group, $element, $ar_user_group);
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>