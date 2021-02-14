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

// vis2_navigation
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_navigation: WHERE page_id=:page_id:');
$QdeleteData->bindTable(':table_vis2_navigation:', 'vis2_navigation');
$QdeleteData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_page
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_page: WHERE page_id=:page_id:');
$QdeleteData->bindTable(':table_vis2_page:', 'vis2_page');
$QdeleteData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

// vis2_page_permission
$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_page_permission: WHERE page_id=:page_id:');
$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
$QdeleteData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
$QdeleteData->execute();

?>