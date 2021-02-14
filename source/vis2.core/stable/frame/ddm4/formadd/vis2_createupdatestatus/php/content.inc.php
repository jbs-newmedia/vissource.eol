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

if (osW_Settings::getInstance()->getAction()=='doadd') {
	$this->setDoAddElementStorage($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_time', $this->getAddElementOption($ddm_group, $element, 'time'));
	$this->setDoAddElementStorage($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_user_id', $this->getAddElementOption($ddm_group, $element, 'user_id'));
	$this->setDoAddElementStorage($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_time', $this->getAddElementOption($ddm_group, $element, 'time'));
	$this->setDoAddElementStorage($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_user_id', $this->getAddElementOption($ddm_group, $element, 'user_id'));

	osW_DDM4::getInstance()->addDataElement($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_time', array(
		'module'=>'hidden',
		'name'=>$this->getAddElementOption($ddm_group, $element, 'prefix').'create_time',
		'options'=>array(
			'default_value'=>$this->getDoAddElementStorage($ddm_group, $element, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_time'),
		),
	));

	osW_DDM4::getInstance()->addDataElement($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_user_id', array(
		'module'=>'hidden',
		'name'=>$this->getAddElementOption($ddm_group, $element, 'prefix').'create_user_id',
		'options'=>array(
			'default_value'=>$this->getDoAddElementStorage($ddm_group, $element, $this->getAddElementOption($ddm_group, $element, 'prefix').'create_user_id'),
		),
	));

	osW_DDM4::getInstance()->addDataElement($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_time', array(
		'module'=>'hidden',
		'name'=>$this->getAddElementOption($ddm_group, $element, 'prefix').'update_time',
		'options'=>array(
			'default_value'=>$this->getDoAddElementStorage($ddm_group, $element, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_time'),
		),
	));

	osW_DDM4::getInstance()->addDataElement($ddm_group, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_user_id', array(
		'module'=>'hidden',
		'name'=>$this->getAddElementOption($ddm_group, $element, 'prefix').'update_user_id',
		'options'=>array(
			'default_value'=>$this->getDoAddElementStorage($ddm_group, $element, $this->getAddElementOption($ddm_group, $element, 'prefix').'update_user_id'),
		),
	));
}

$this->incCounter($ddm_group, 'form_elements');
$this->incCounter($ddm_group, 'form_elements_required');

?>