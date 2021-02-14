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

// vis_group / vis_group_permission
$QgetData=osW_Database::getInstance()->query('SELECT group_id FROM :table_vis_group: WHERE tool_id=:tool_id:');
$QgetData->bindTable(':table_vis_group:', 'vis_group');
$QgetData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QgetData->execute();
if ($QgetData->numberOfRows()>0) {
	while ($QgetData->next()) {
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group: WHERE tool_id=:tool_id: AND group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis_group:', 'vis_group');
		$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
		$QdeleteData->bindInt(':group_id:', $QgetData->Value('group_id'));
		$QdeleteData->execute();

		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group_permission: WHERE group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
		$QdeleteData->bindInt(':group_id:', $QgetData->Value('group_id'));
		$QdeleteData->execute();
	}
}

// vis_mandant
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_mandant: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis_mandant:', 'vis_mandant');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis_navigation / vis_navigation_permission
$QgetData=osW_Database::getInstance()->query('SELECT navigation_id FROM :table_vis_navigation: WHERE tool_id=:tool_id:');
$QgetData->bindTable(':table_vis_navigation:', 'vis_navigation');
$QgetData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QgetData->execute();
if ($QgetData->numberOfRows()>0) {
	while ($QgetData->next()) {
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_navigation: WHERE tool_id=:tool_id: AND navigation_id=:navigation_id:');
		$QdeleteData->bindTable(':table_vis_navigation:', 'vis_navigation');
		$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
		$QdeleteData->bindInt(':navigation_id:', $QgetData->Value('navigation_id'));
		$QdeleteData->execute();

		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_navigation_permission: WHERE navigation_id=:navigation_id:');
		$QdeleteData->bindTable(':table_vis_navigation_permission:', 'vis_navigation_permission');
		$QdeleteData->bindInt(':navigation_id:', $QgetData->Value('navigation_id'));
		$QdeleteData->execute();
	}
}

// vis_permission
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_permission: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis_permission:', 'vis_permission');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis_user_group
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_group: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis_user_group:', 'vis_user_group');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis_user_tool
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_tool: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

?>