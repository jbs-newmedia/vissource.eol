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
	case 'add':
		if ($this->getCounter($ddm_group, 'add_elements')>0) {
			osW_Settings::getInstance()->setAction('add');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'doadd':
		if ($this->getCounter($ddm_group, 'add_elements')>0) {
			osW_Settings::getInstance()->setAction('doadd');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'edit':
		if ($this->getCounter($ddm_group, 'edit_elements')>0) {
			osW_Settings::getInstance()->setAction('edit');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'doedit':
		if ($this->getCounter($ddm_group, 'edit_elements')>0) {
			osW_Settings::getInstance()->setAction('doedit');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'delete':
		if ($this->getCounter($ddm_group, 'delete_elements')>0) {
			osW_Settings::getInstance()->setAction('delete');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'dodelete':
		if ($this->getCounter($ddm_group, 'delete_elements')>0) {
			osW_Settings::getInstance()->setAction('dodelete');
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	default:
		osW_Settings::getInstance()->setAction('');
		break;
}

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', $this->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

// Add
if ((osW_Settings::getInstance()->getAction()=='add')||(osW_Settings::getInstance()->getAction()=='doadd')) {
	$this->setIndexElementStorage($ddm_group, h()->_catch(0, '', 'pg'));

	foreach ($this->getAddElements($ddm_group) as $element => $options) {
		$this->parseFormAddElementPHP($ddm_group, $element, $options);
	}

	if (osW_Settings::getInstance()->getAction()=='doadd') {
		if (strlen(h()->_catch('btn_ddm_cancel', '', 'p'))>0) {
			osW_Settings::getInstance()->setAction('');
			$_POST=array();
		}
	}

	if ((osW_Settings::getInstance()->getAction()=='add')||(osW_Settings::getInstance()->getAction()=='doadd')) {
		foreach ($this->getAddElements($ddm_group) as $element => $element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setAddElementStorage($ddm_group, $element, $this->getAddElementOption($ddm_group, $element, 'default_value'));
			}
		}

		if (osW_Settings::getInstance()->getAction()=='doadd') {
			foreach ($this->getAddElements($ddm_group) as $element => $options) {
				$options=$this->getAddElementValue($ddm_group, $element, 'validation');
				if ($options!='') {
					$this->parseParserAddElementPHP($ddm_group, $element, $options);
				}
			}

			if (osW_MessageStack::getInstance()->size('form')) {
				osW_Settings::getInstance()->setAction('add');
			} else {
				foreach ($this->getAddElements($ddm_group) as $element => $options) {
					$this->parseFinishAddElementPHP($ddm_group, $element, $options);
				}

				foreach ($this->getFinishElements($ddm_group) as $element => $options) {
					$this->parseFinishAddElementPHP($ddm_group, $element, $options);
				}

				foreach ($this->getAfterFinishElements($ddm_group) as $element => $options) {
					$this->parseFinishAddElementPHP($ddm_group, $element, $options);
				}
			}
		}
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>$this->getGroupMessage($ddm_group, 'add_load_error_title')));
		$this->direct($ddm_group, $this->getDirectModule($ddm_group), $this->getDirectParameters($ddm_group));
	}
}



// Edit
if ((osW_Settings::getInstance()->getAction()=='edit')||(osW_Settings::getInstance()->getAction()=='doedit')) {
	$this->setIndexElementStorage($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database'), '', 'pg'));

	$database_where_string='';
	$ddm_filter_array=$this->getGroupOption($ddm_group, 'filter', 'database');
	if (($ddm_filter_array!='')&&($ddm_filter_array!=array())) {
		$ddm_filter=array();
		foreach ($ddm_filter_array as $filter) {
			$ar_values=array();
			foreach ($filter as $logic => $elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_filter[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
		$database_where_string.= ' AND ('.implode(' OR ', $ddm_filter).')';
	}

	$QloadData=osW_Database::getInstance()->query('SELECT :vars: FROM :table: AS :alias: WHERE :name_index:=:value_index: :where:');
	$QloadData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
	$QloadData->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
	$QloadData->bindRaw(':vars:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.implode(', '.$this->getGroupOption($ddm_group, 'alias', 'database').'.', $this->getEditElementsName($ddm_group)));
	$QloadData->bindRaw(':name_index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	if ($this->getGroupOption($ddm_group, 'db_index_type', 'database')=='string') {
		$QloadData->bindValue(':value_index:', $this->getIndexElementStorage($ddm_group));
	} else {
		$QloadData->bindInt(':value_index:', $this->getIndexElementStorage($ddm_group));
	}
	$QloadData->bindRaw(':where:', $database_where_string);
	$QloadData->execute();
	if ($QloadData->query_handler===false) {
		print_a($QloadData);
	}

	if ($QloadData->numberOfRows()===1) {
		$QloadData->next();
		foreach ($this->getEditElements($ddm_group) as $element => $element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setEditElementStorage($ddm_group, $element, $QloadData->Value($element_details['name']));
			}
			if ((isset($element_details['name_array']))&&($element_details['name_array']!=array())) {
				foreach ($element_details['name_array'] as $_name) {
					if ($element_details['options']['prefix']!='') {
						$this->setEditElementStorage($ddm_group, $element.'_'.$element_details['options']['prefix'].$_name, $QloadData->Value($element_details['options']['prefix'].$_name));
					} else {
						$this->setEditElementStorage($ddm_group, $element.'_'.$_name, $QloadData->Value($_name));
					}
				}
			}
		}
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>$this->getGroupMessage($ddm_group, 'edit_load_error_title')));
		$this->direct($ddm_group, $this->getDirectModule($ddm_group), $this->getDirectParameters($ddm_group));
	}

	foreach ($this->getEditElements($ddm_group) as $element => $options) {
		$this->parseFormEditElementPHP($ddm_group, $element, $options);
	}

	if (osW_Settings::getInstance()->getAction()=='doedit') {
		if (strlen(h()->_catch('btn_ddm_cancel', '', 'p'))>0) {
			osW_Settings::getInstance()->setAction('');
			$_POST=array();
		}
	}

	if (osW_Settings::getInstance()->getAction()=='doedit') {
		foreach ($this->getEditElements($ddm_group) as $element => $options) {
			$options=$this->getEditElementValue($ddm_group, $element, 'validation');
			if ($options!='') {
				$this->parseParserEditElementPHP($ddm_group, $element, $options);
			}
		}

		if (osW_MessageStack::getInstance()->size('form')) {
			osW_Settings::getInstance()->setAction('edit');
		} else {
			foreach ($this->getEditElements($ddm_group) as $element => $options) {
				$this->parseFinishEditElementPHP($ddm_group, $element, $options);
			}

			foreach ($this->getFinishElements($ddm_group) as $element => $options) {
				$this->parseFinishEditElementPHP($ddm_group, $element, $options);
			}

			foreach ($this->getAfterFinishElements($ddm_group) as $element => $options) {
				$this->parseFinishEditElementPHP($ddm_group, $element, $options);
			}
		}
	}
}



// Delete
if ((osW_Settings::getInstance()->getAction()=='delete')||(osW_Settings::getInstance()->getAction()=='dodelete')) {
	$this->setIndexElementStorage($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database'), '', 'pg'));

	$database_where_string='';
	if (($ddm_filter_array!='')&&($ddm_filter_array!=array())) {
		$ddm_filter=array();
		foreach ($ddm_filter_array as $filter) {
			$ar_values=array();
			foreach ($filter as $logic => $elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_filter[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
		$database_where_string.= ' AND ('.implode(' OR ', $ddm_filter).')';
	}

	$QloadData=osW_Database::getInstance()->query('SELECT :vars: FROM :table: AS :alias: WHERE :name_index:=:value_index: :where:');
	$QloadData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
	$QloadData->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
	$QloadData->bindRaw(':vars:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.implode(', '.$this->getGroupOption($ddm_group, 'alias', 'database').'.', $this->getDeleteElementsName($ddm_group)));
	$QloadData->bindRaw(':name_index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	if ($this->getGroupOption($ddm_group, 'db_index_type', 'database')=='string') {
		$QloadData->bindValue(':value_index:', $this->getIndexElementStorage($ddm_group));
	} else {
		$QloadData->bindInt(':value_index:', $this->getIndexElementStorage($ddm_group));
	}
	$QloadData->bindRaw(':where:', $database_where_string);
	$QloadData->execute();
	if ($QloadData->query_handler===false) {
		print_a($QloadData);
	}

	if ($QloadData->numberOfRows()===1) {
		$QloadData->next();
		foreach ($this->getDeleteElements($ddm_group) as $element => $element_details) {
			if ((isset($element_details['name']))&&($element_details['name']!='')) {
				$this->setDeleteElementStorage($ddm_group, $element, $QloadData->Value($element_details['name']));
			}
			if ((isset($element_details['name_array']))&&($element_details['name_array']!=array())) {
				foreach ($element_details['name_array'] as $_name) {
					if ($element_details['options']['prefix']!='') {
						$this->setDeleteElementStorage($ddm_group, $element.'_'.$element_details['options']['prefix'].$_name, $QloadData->Value($element_details['options']['prefix'].$_name));
					} else {
						$this->setDeleteElementStorage($ddm_group, $element.'_'.$_name, $QloadData->Value($_name));
					}
				}
			}
		}
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>$this->getGroupMessage($ddm_group, 'delete_load_error_title')));
		$this->direct($ddm_group, $this->getDirectModule($ddm_group), $this->getDirectParameters($ddm_group));
	}

	foreach ($this->getDeleteElements($ddm_group) as $element => $options) {
		$this->parseFormDeleteElementPHP($ddm_group, $element, $options);
	}

	if (osW_Settings::getInstance()->getAction()=='dodelete') {
		if (strlen(h()->_catch('btn_ddm_cancel', '', 'p'))>0) {
			osW_Settings::getInstance()->setAction('');
			$_POST=array();
		}
	}

	if (osW_Settings::getInstance()->getAction()=='dodelete') {
		if (osW_MessageStack::getInstance()->size('form')) {
			osW_Settings::getInstance()->setAction('delete');
		} else {
			foreach ($this->getDeleteElements($ddm_group) as $element => $options) {
				$this->parseFinishDeleteElementPHP($ddm_group, $element, $options);
			}

			foreach ($this->getFinishElements($ddm_group) as $element => $options) {
				$this->parseFinishDeleteElementPHP($ddm_group, $element, $options);
			}

			foreach ($this->getAfterFinishElements($ddm_group) as $element => $options) {
				$this->parseFinishDeleteElementPHP($ddm_group, $element, $options);
			}
		}
	}
}


// List
if (osW_Settings::getInstance()->getAction()=='') {

}

$this->storeParameters($ddm_group);

?>