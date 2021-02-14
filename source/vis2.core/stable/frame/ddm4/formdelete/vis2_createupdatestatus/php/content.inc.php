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

if ((osW_Settings::getInstance()->getAction()=='delete')||(osW_Settings::getInstance()->getAction()=='dodelete')) {
	$Qselect=osW_Database::getInstance()->query('SELECT :elements: FROM :table: AS :alias: WHERE :name_index:=:value_index:');
	$Qselect->bindRaw(':elements:', implode(', ', array($this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_time', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_user_id', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_time',$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_user_id')));
	$Qselect->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
	$Qselect->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
	$Qselect->bindRaw(':name_index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	if ($this->getGroupOption($ddm_group, 'db_index_type', 'database')=='string') {
		$Qselect->bindValue(':value_index:', $this->getIndexElementStorage($ddm_group));
	} else {
		$Qselect->bindInt(':value_index:', $this->getIndexElementStorage($ddm_group));
	}
	$Qselect->execute();
	if ($Qselect->numberOfRows()==1) {
		$Qselect->next();

		if (osW_Settings::getInstance()->getAction()=='delete') {
			$this->setDeleteElementStorage($ddm_group, $this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_time', $Qselect->result[$this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_time']);
			$this->setDeleteElementStorage($ddm_group, $this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_user_id', $Qselect->result[$this->getDeleteElementOption($ddm_group, $element, 'prefix').'create_user_id']);
			$this->setDeleteElementStorage($ddm_group, $this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_time', $Qselect->result[$this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_time']);
			$this->setDeleteElementStorage($ddm_group, $this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_user_id', $Qselect->result[$this->getDeleteElementOption($ddm_group, $element, 'prefix').'update_user_id']);
		}
	}
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>