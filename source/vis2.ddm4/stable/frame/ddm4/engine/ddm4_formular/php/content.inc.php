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

$this->readParameters($ddm_group);

switch (osW_Settings::getInstance()->getAction()) {
	case 'dosend':
		osW_Settings::getInstance()->setAction('dosend');
		break;
	default:
		osW_Settings::getInstance()->setAction('send');
		break;
}

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', $this->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

// Send
if ((osW_Settings::getInstance()->getAction()=='send')||(osW_Settings::getInstance()->getAction()=='dosend')) {
	$this->setIndexElementStorage($ddm_group, h()->_catch(0, '', 'pg'));

	foreach ($this->getSendElements($ddm_group) as $element => $element_details) {
		$this->setSendElementStorage($ddm_group, $element, $this->getSendElementOption($ddm_group, $element, 'default_value'));
	}

	foreach ($this->getSendElements($ddm_group) as $element => $options) {
		$this->parseFormSendElementPHP($ddm_group, $element, $options);
	}

	if (osW_Settings::getInstance()->getAction()=='dosend') {
		if (strlen(h()->_catch('btn_ddm_cancel', '', 'p'))>0) {
			osW_Settings::getInstance()->setAction('');
			$_POST=array();
		}
	}

	if ((osW_Settings::getInstance()->getAction()=='send')||(osW_Settings::getInstance()->getAction()=='dosend')) {
		foreach ($this->getSendElements($ddm_group) as $element => $element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setSendElementStorage($ddm_group, $element, $this->getSendElementOption($ddm_group, $element, 'default_value'));
			}
		}

		if (osW_Settings::getInstance()->getAction()=='dosend') {
			foreach ($this->getSendElements($ddm_group) as $element => $options) {
				$options=$this->getSendElementValue($ddm_group, $element, 'validation');
				if ($options!='') {
					$this->parseParserSendElementPHP($ddm_group, $element, $options);
				}
			}

			if (osW_Form::getInstance()->hasFormError()) {
				osW_Settings::getInstance()->setAction('send');
			} else {
				foreach ($this->getSendElements($ddm_group) as $element => $options) {
					$this->parseFinishSendElementPHP($ddm_group, $element, $options);
				}

				foreach ($this->getFinishElements($ddm_group) as $element => $options) {
					$this->parseFinishSendElementPHP($ddm_group, $element, $options);
				}

				foreach ($this->getAfterFinishElements($ddm_group) as $element => $options) {
					$this->parseFinishSendElementPHP($ddm_group, $element, $options);
				}
			}
		}
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>$this->getGroupMessage($ddm_group, 'send_load_error_title')));
		$this->direct($ddm_group, osW_Template::getInstance()->buildhrefLink($this->getDirectModule($ddm_group), $this->getDirectParameters($ddm_group)));
	}
}

$this->storeParameters($ddm_group);

?>