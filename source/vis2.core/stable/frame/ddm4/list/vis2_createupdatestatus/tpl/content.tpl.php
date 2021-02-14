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

if($this->getListElementOption($ddm_group, $element, 'display_create_time')==true) {
	if(($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']=='')||($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']=='0')) {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']='---';
	} else {
		if($this->getListElementOption($ddm_group, $element, 'month_asname')===true) {
			$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($ddm_group, $element, 'date_format')), $view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']).' '.h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'));
		} else {
			$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']=strftime($this->getListElementOption($ddm_group, $element, 'date_format'), $view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_time']).' '.h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'));
		}
	}
}

if($this->getListElementOption($ddm_group, $element, 'display_create_user')==true) {
	if(($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id']=='')||($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id']=='0')||(osW_VIS2::getInstance()->getUsernameById($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id'])=='')) {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id']='---';
	} else {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id']=h()->_outputString(osW_VIS2::getInstance()->getUsernameById($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'create_user_id']));
	}
}

if($this->getListElementOption($ddm_group, $element, 'display_update_time')==true) {
	if(($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']=='')||($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']=='0')) {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']='---';
	} else {
		if($this->getListElementOption($ddm_group, $element, 'month_asname')===true) {
			$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($ddm_group, $element, 'date_format')), $view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']).' '.h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'));
		} else {
			$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']=strftime($this->getListElementOption($ddm_group, $element, 'date_format'), $view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_time']).' '.h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'));
		}
	}
}

if($this->getListElementOption($ddm_group, $element, 'display_update_user')==true) {
	if(($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id']=='')||($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id']=='0')||(osW_VIS2::getInstance()->getUsernameById($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id'])=='')) {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id']='---';
	} else {
		$view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id']=h()->_outputString(osW_VIS2::getInstance()->getUsernameById($view_data[$this->getListElementOption($ddm_group, $element, 'prefix').'update_user_id']));
	}
}

?>