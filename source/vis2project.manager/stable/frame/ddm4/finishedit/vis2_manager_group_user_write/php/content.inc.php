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

$ar_tool_user=$this->getEditElementStorage($ddm_group, substr($element, 0, -6));
$ar_tool_user_do=$this->getDoEditElementStorage($ddm_group, substr($element, 0, -6));

$element_storage='ddm4_store_form_data';
$element_current='vis2_group_user';
$element_more='vis2_user_group';
$group_more='vis2_user';

if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
	$vis_user_id=$this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id');
	$vis_time=$this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time');
} else {
	$vis_time=time();
	$vis_user_id=osW_VIS2_User::getInstance()->getId();
}

if ($this->getFinishElementOption($ddm_group, $element, 'group')!='') {
	$group=$this->getFinishElementOption($ddm_group, $element, 'group');
} else {
	$group=$this->getGroupOption($ddm_group, 'table', 'database');
}

$ar_user=osW_VIS2_Manager::getInstance()->getUsers();
$tool_groups=osW_VIS2_Manager::getInstance()->getGroups($_POST['tool_id']);
foreach ($ar_tool_user_do as $user_id => $flag) {
	if ((!isset($ar_tool_user[$user_id]))||($ar_tool_user[$user_id]!==$flag)) {
		if ($flag==1) {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis2_user_group: (group_id, user_id, tool_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:group_id:, :user_id:, :tool_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
			$QinsertData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
			$QinsertData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
			$QinsertData->bindInt(':user_id:', $user_id);
			$QinsertData->bindInt(':tool_id:', $_POST['tool_id']);
			$QinsertData->bindInt(':user_group_create_time:', $vis_time);
			$QinsertData->bindInt(':user_group_create_user_id:', $vis_user_id);
			$QinsertData->bindInt(':user_group_update_time:', $vis_time);
			$QinsertData->bindInt(':user_group_update_user_id:', $vis_user_id);
			$QinsertData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_user[$user_id], '✔ '.$ar_user[$user_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_user[$user_id], '✔ '.$ar_user[$user_id]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)');
				}
				osW_DDM4_Log::getInstance()->writeValues($group_more, 'user_id', $user_id);
			}
		} elseif ((isset($ar_tool_user[$user_id]))&&($flag==0)) {
			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_group: WHERE group_id=:group_id: AND user_id=:user_id: AND tool_id=:tool_id:');
			$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
			$QdeleteData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
			$QdeleteData->bindInt(':user_id:', $user_id);
			$QdeleteData->bindInt(':tool_id:', $_POST['tool_id']);
			$QdeleteData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_user[$user_id], '✘ '.$ar_user[$user_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_user[$user_id], '✘ '.$ar_user[$user_id]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)', '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$this->getIndexElementStorage($ddm_group)].' (Über Gruppe geändert)');
				}
				osW_DDM4_Log::getInstance()->writeValues($group_more, 'user_id', $user_id);
			}
		}
	}
}

?>