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

$ar_tool_user=array();
$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_user_group: WHERE group_id=:group_id: AND tool_id=:tool_id:');
$QloadData->bindTable(':table_vis_user_group:', 'vis_user_group');
$QloadData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
$QloadData->bindInt(':tool_id:', $_POST['tool_id']);
$QloadData->execute();
if ($QloadData->numberOfRows()>0) {
	while ($QloadData->next()>0) {
		$ar_tool_user[$QloadData->Value('user_id')]=1;
	}
}
$this->setDeleteElementStorage($ddm_group, $element, $ar_tool_user);

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>