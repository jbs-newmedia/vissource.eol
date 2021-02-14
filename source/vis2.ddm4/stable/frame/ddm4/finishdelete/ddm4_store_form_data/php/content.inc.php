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

$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table: WHERE :name_index:=:value_index:');
$QdeleteData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
$QdeleteData->bindRaw(':name_index:', $this->getGroupOption($ddm_group, 'index', 'database'));
if ($this->getGroupOption($ddm_group, 'db_index_type', 'database')=='string') {
	$QdeleteData->bindValue(':value_index:', $this->getIndexElementStorage($ddm_group));
} else {
	$QdeleteData->bindInt(':value_index:', $this->getIndexElementStorage($ddm_group));
}
$QdeleteData->execute();
if ($QdeleteData->query_handler===false) {
	print_a($QdeleteData);
	h()->_die();
}

osW_MessageStack::getInstance()->add('ddm4_'.$ddm_group, 'success', array('msg'=>$this->getGroupMessage($ddm_group, 'delete_success_title')));

?>