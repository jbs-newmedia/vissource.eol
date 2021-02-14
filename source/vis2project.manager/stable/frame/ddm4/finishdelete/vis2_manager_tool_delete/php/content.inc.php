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

$QgetData=osW_Database::getInstance()->query('SELECT group_id FROM :table_vis2_group: WHERE tool_id=:tool_id:');
$QgetData->bindTable(':table_vis2_group:', 'vis2_group');
$QgetData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QgetData->execute();
if ($QgetData->numberOfRows()>0) {
	while ($QgetData->next()) {

		// vis2_group_permission
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_group_permission: WHERE group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
		$QdeleteData->bindInt(':group_id:', $QgetData->result['group_id']);
		$QdeleteData->execute();

		// vis2_user_group
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_group: WHERE group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
		$QdeleteData->bindInt(':group_id:', $QgetData->result['group_id']);
		$QdeleteData->execute();
	}
}

// vis2_group
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_group: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_group:', 'vis2_group');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_mandant
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_mandant: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_mandant:', 'vis2_mandant');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_navigation
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_navigation: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_navigation:', 'vis2_navigation');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_page
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_page: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_page:', 'vis2_page');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_page_permission
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_page_permission: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_permission
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_permission: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_permission:', 'vis2_permission');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_protect
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_protect: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_protect:', 'vis2_protect');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_tool
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_tool: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_tool:', 'vis2_tool');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_user_group
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_group: WHERE tool_id=:tool_id:');
$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
$QdeleteData->bindInt(':tool_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_user_tool
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_tool: WHERE user_id=:user_id:');
$QdeleteData->bindTable(':table_vis2_user_tool:', 'vis2_user_tool');
$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

?>