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

if ($this->getIndexElementStorage($ddm_group)==$this->getDoEditElementStorage($ddm_group, 'navigation_parent_id')) {
	osW_Form::getInstance()->addFormError($element, h()->_setText($this->getGroupMessage($ddm_group, 'validation_element_incorrect'), $this->getFilterElementStorage($ddm_group, $element)));
	osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>h()->_setText($this->getGroupMessage($ddm_group, 'validation_element_incorrect'), $this->getFilterElementStorage($ddm_group, $element))));
	$this->setFilterErrorElementStorage($ddm_group, $element, true);
}

?>