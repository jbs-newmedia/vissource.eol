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

$vars=array();
foreach ($this->getEditElements($ddm_group) as $element_name=> $element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		$vars[]=$element_details['name'].'=:'.$element_name.':';
	}
}

$QsaveData=osW_Database::getInstance()->query('UPDATE :table: AS :alias: SET :vars: WHERE :name_index:=:value_index:');
$QsaveData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
$QsaveData->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
$QsaveData->bindRaw(':vars:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.implode(',  '.$this->getGroupOption($ddm_group, 'alias', 'database').'.', $vars));
$QsaveData->bindRaw(':name_index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
if ($this->getGroupOption($ddm_group, 'db_index_type', 'database')=='string') {
	$QsaveData->bindValue(':value_index:', $this->getIndexElementStorage($ddm_group));
} else {
	$QsaveData->bindInt(':value_index:', $this->getIndexElementStorage($ddm_group));
}

foreach ($this->getEditElements($ddm_group) as $element_name => $element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		switch($this->getEditElementValidation($ddm_group, $element_name, 'module')) {
			case 'integer':
				$QsaveData->bindInt(':'.$element_name.':', $this->getDoEditElementStorage($ddm_group, $element_name));
				break;
			case 'float':
				$QsaveData->bindFloat(':'.$element_name.':', $this->getDoEditElementStorage($ddm_group, $element_name));
				break;
			case 'crypt':
				$QsaveData->bindCrypt(':'.$element_name.':', $this->getDoEditElementStorage($ddm_group, $element_name));
				break;
			case 'raw':
				$QsaveData->bindRaw(':'.$element_name.':', $this->getDoEditElementStorage($ddm_group, $element_name));
				break;
			case 'string':
			default:
				$QsaveData->bindValue(':'.$element_name.':', $this->getDoEditElementStorage($ddm_group, $element_name));
				break;
		}
	}
}
$QsaveData->execute();
if ($QsaveData->query_handler===false) {
	print_a($QsaveData);
	h()->_die();
}

if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
	$vars=array();

	if ($this->getFinishElementOption($ddm_group, $element, 'group')!='') {
		$group=$this->getFinishElementOption($ddm_group, $element, 'group');
	} else {
		$group=$this->getGroupOption($ddm_group, 'table', 'database');
	}

	foreach ($this->getEditElements($ddm_group) as $element_name => $element_details) {
		if ((isset($element_details['name']))&&($element_details['name']!='')) {
			$value_old=$this->getEditElementStorage($ddm_group, $element_name);
			$value_new=$this->getDoEditElementStorage($ddm_group, $element_name);

			switch($this->getEditElementValidation($ddm_group, $element_name, 'module')) {
				case 'integer':
					$value_new=intval($value_new);
					break;
				case 'float':
					$value_new=floatval($value_new);
					$value_new=trim($value_new);
				case 'raw':
					break;
				case 'crypt':
					break;
				case 'raw':
					break;
				case 'string':
				default:
					break;
			}

			if ($value_old!=$value_new) {
				$file=vOut('settings_abspath').'frame/ddm4/list/'.$element_details['module'].'/php/log.inc.php';
				if (file_exists($file)) {
					include $file;
				}

				if ($this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_name, [$this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_name, $value_old, $value_new, $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_name, $value_old, $value_new);
				}
			}
		}
	}
	osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
}

osW_MessageStack::getInstance()->add('ddm4_'.$ddm_group, 'success', array('msg'=>$this->getGroupMessage($ddm_group, 'edit_success_title')));

?>