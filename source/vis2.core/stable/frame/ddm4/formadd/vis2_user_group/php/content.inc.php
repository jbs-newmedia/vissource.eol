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

$this->setAddElementStorage($ddm_group, $element, array());

if (osW_Settings::getInstance()->getAction()=='doadd') {
	$ar_user_group=array();
	foreach(osW_VIS2_Manager::getInstance()->getGroups(osW_VIS2::getInstance()->getToolID()) as $group_id => $group_name_intern) {
		if ((isset($_POST[$element.'_'.$group_id]))&&($_POST[$element.'_'.$group_id]==1)) {
			$ar_user_group[osW_VIS2::getInstance()->getToolID()][$group_id]=1;
		} else {
			$ar_user_group[osW_VIS2::getInstance()->getToolID()][$group_id]=0;
			if (!isset($_POST[$element.'_'.$group_id])) {
				$_POST[$element.'_'.$group_id]=0;
			}
		}
	}
	$this->setDoAddElementStorage($ddm_group, $element, $ar_user_group);
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>