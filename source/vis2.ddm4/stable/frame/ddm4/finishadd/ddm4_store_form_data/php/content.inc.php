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

foreach ($this->getAddElements($ddm_group) as $element => $element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		$vars_key[]=$element_details['name'];
		$vars_value[]=$element;
	}
}

$QsaveData=osW_Database::getInstance()->query('INSERT INTO :table: (:vars_name:) VALUES (:vars_value:)');
$QsaveData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
$QsaveData->bindRaw(':vars_name:', implode(', ', $vars_key));
$QsaveData->bindRaw(':vars_value:', ':'.implode(':, :', $vars_value).':');
foreach ($this->getAddElements($ddm_group) as $element => $element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		switch($this->getAddElementValidation($ddm_group, $element, 'module')) {
			case 'integer':
				$QsaveData->bindInt(':'.$element.':', $this->getDoAddElementStorage($ddm_group, $element));
				break;
			case 'float':
				$QsaveData->bindFloat(':'.$element.':', $this->getDoAddElementStorage($ddm_group, $element));
				break;
			case 'crypt':
				$QsaveData->bindCrypt(':'.$element.':', $this->getDoAddElementStorage($ddm_group, $element));
				break;
			case 'raw':
				$QsaveData->bindRaw(':'.$element.':', $this->getDoAddElementStorage($ddm_group, $element));
				break;
			case 'string':
			default:
				$QsaveData->bindValue(':'.$element.':', $this->getDoAddElementStorage($ddm_group, $element));
				break;
		}
	}
}
$QsaveData->execute();
if ($QsaveData->query_handler===false) {
	print_a($QsaveData);
	h()->_die();
}

$this->setIndexElementStorage($ddm_group, $QsaveData->nextID());

osW_MessageStack::getInstance()->add('ddm4_'.$ddm_group, 'success', array('msg'=>$this->getGroupMessage($ddm_group, 'add_success_title')));

?>