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
$element_current='vis2_user_tool';

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

$ar_tools=osW_VIS2::getInstance()->getToolsListArray();

foreach ($ar_tool_user_do as $tool_id => $flag) {
	if ((!isset($ar_tool_user[$tool_id]))||($ar_tool_user[$tool_id]!==$flag)) {
		if ($flag==1) {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis2_user_tool: (user_id, tool_id, user_tool_create_time, user_tool_create_user_id, user_tool_update_time, user_tool_update_user_id) VALUES (:user_id:, :tool_id:, :user_tool_create_time:, :user_tool_create_user_id:, :user_tool_update_time:, :user_tool_update_user_id:)');
			$QinsertData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
			$QinsertData->bindInt(':tool_id:', $tool_id);
			$QinsertData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
			$QinsertData->bindInt(':user_tool_create_time:', $vis_time);
			$QinsertData->bindInt(':user_tool_create_user_id:', $vis_user_id);
			$QinsertData->bindInt(':user_tool_update_time:', $vis_time);
			$QinsertData->bindInt(':user_tool_update_user_id:', $vis_user_id);
			$QinsertData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_tools[$tool_id], '✔ '.$ar_tools[$tool_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_tools[$tool_id], '✔ '.$ar_tools[$tool_id]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
		} elseif ((isset($ar_tool_user[$tool_id]))&&($flag==0)) {
			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
			$QdeleteData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
			$QdeleteData->bindInt(':tool_id:', $tool_id);
			$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
			$QdeleteData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_tools[$tool_id], '✘ '.$ar_tools[$tool_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_tools[$tool_id], '✘ '.$ar_tools[$tool_id]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
		}
	}
}

?>