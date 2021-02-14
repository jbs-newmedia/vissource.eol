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

// vis_user
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user: WHERE user_id=:user_id:');
$QdeleteData->bindTable(':table_vis_user:', 'vis_user');
$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis_user_pref
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_pref: WHERE user_id=:user_id:');
$QdeleteData->bindTable(':table_vis_user_pref:', 'vis_user_pref');
$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis_user_pref
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_tool: WHERE user_id=:user_id:');
$QdeleteData->bindTable(':table_vis_user_tool:', 'vis_user_tool');
$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

?>