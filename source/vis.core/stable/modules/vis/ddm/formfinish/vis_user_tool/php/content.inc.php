<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
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

$element='user_tool';

switch (osW_Settings::getInstance()->getAction()) {
	case 'doadd':
		$this->setFormDataElement($ddm_group, $element, h()->_settype(h()->_catch($element, 0, 'p'), 'integer'));
		
		if ($this->getFormDataElement($ddm_group, $element)=='1') {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_user_tool: (user_id, tool_id) VALUES (:user_id:, :tool_id:)');
			$QinsertData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
			$QinsertData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
			$QinsertData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
			$QinsertData->execute();
		}
		break;
	case 'doedit':
		$this->setFormDataElement($ddm_group, $element, h()->_settype(h()->_catch($element, 0, 'p'), 'integer'));
		
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
		$QdeleteData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
		$QdeleteData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QdeleteData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
		$QdeleteData->execute();
		
		if ($this->getFormDataElement($ddm_group, $element)=='1') {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_user_tool: (user_id, tool_id) VALUES (:user_id:, :tool_id:)');
			$QinsertData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
			$QinsertData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
			$QinsertData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
			$QinsertData->execute();
		}
		break;
	case 'dodelete':
		$this->setFormDataElement($ddm_group, $element, h()->_settype(h()->_catch($element, '', 'p'), 'string'));
		
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_tool: WHERE user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
		$QdeleteData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QdeleteData->execute();
		break;
}

?>