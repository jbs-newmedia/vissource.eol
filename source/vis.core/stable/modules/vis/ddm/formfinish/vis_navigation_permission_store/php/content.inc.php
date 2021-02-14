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


switch (osW_Settings::getInstance()->getAction()) {
	case 'doadd':
	case 'doedit':
		if (strstr($this->getFormDataElement($ddm_group, 'navigation_group_id'), '_')) {
			$_tmp=explode('_', $this->getFormDataElement($ddm_group, 'navigation_group_id'));

			$this->setFormDataElement($ddm_group, 'navigation_group_id', $_tmp[0]);
			$this->setFormDataElement($ddm_group, 'navigation_page_id', $_tmp[1]);
		}
		break;
	case 'dodelete':
		break;
}

?>