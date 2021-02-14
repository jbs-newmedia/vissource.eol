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


$i=0;

$QselectDataL1=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
$QselectDataL1->bindTable(':table_vis2_navigation:', 'vis2_navigation');
$QselectDataL1->bindInt(':navigation_parent_id:', 0);
$QselectDataL1->execute();
if ($QselectDataL1->numberOfRows()>0) {
	while ($QselectDataL1->next()) {
		$QupdateDataL1=osW_Database::getInstance()->query('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
		$QupdateDataL1->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QupdateDataL1->bindInt(':navigation_intern_sortorder:', $i);
		$QupdateDataL1->bindInt(':navigation_id:', $QselectDataL1->result['navigation_id']);
		$QupdateDataL1->execute();
		$i++;

		$QselectDataL2=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
		$QselectDataL2->bindTable(':table_vis2_navigation:', 'vis2_navigation');
		$QselectDataL2->bindInt(':navigation_parent_id:', $QselectDataL1->result['navigation_id']);
		$QselectDataL2->execute();
		if ($QselectDataL2->numberOfRows()>0) {
			while ($QselectDataL2->next()) {
				$QupdateDataL2=osW_Database::getInstance()->query('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
				$QupdateDataL2->bindTable(':table_vis2_navigation:', 'vis2_navigation');
				$QupdateDataL2->bindInt(':navigation_intern_sortorder:', $i);
				$QupdateDataL2->bindInt(':navigation_id:', $QselectDataL2->result['navigation_id']);
				$QupdateDataL2->execute();
				$i++;

				$QselectDataL3=osW_Database::getInstance()->query('SELECT * FROM :table_vis2_navigation: WHERE navigation_parent_id=:navigation_parent_id: ORDER BY navigation_sortorder ASC');
				$QselectDataL3->bindTable(':table_vis2_navigation:', 'vis2_navigation');
				$QselectDataL3->bindInt(':navigation_parent_id:', $QselectDataL2->result['navigation_id']);
				$QselectDataL3->execute();
				if ($QselectDataL3->numberOfRows()>0) {
					while ($QselectDataL3->next()) {
						$QupdateDataL3=osW_Database::getInstance()->query('UPDATE :table_vis2_navigation: SET navigation_intern_sortorder=:navigation_intern_sortorder: WHERE navigation_id=:navigation_id:');
						$QupdateDataL3->bindTable(':table_vis2_navigation:', 'vis2_navigation');
						$QupdateDataL3->bindInt(':navigation_intern_sortorder:', $i);
						$QupdateDataL3->bindInt(':navigation_id:', $QselectDataL3->result['navigation_id']);
						$QupdateDataL3->execute();
						$i++;
					}
				}
			}
		}
	}
}

?>