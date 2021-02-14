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

if(osW_Settings::getInstance()->getAction()=='send') {
	echo '<div class="card shadow mb-4"><div class="card-body">';

	echo osW_Form::getInstance()->formStart($ddm_group, 'current', $this->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'));
	foreach ($this->getSendElements($ddm_group) as $element => $options) {
		echo $this->parseFormSendElementTPL($ddm_group, $element, $options);
	}
	echo osW_Form::getInstance()->drawHiddenField('action', 'dosend');
	echo osW_Form::getInstance()->drawHiddenField($this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
	echo osW_Form::getInstance()->formEnd();

	echo '</div></div>';
}

?>