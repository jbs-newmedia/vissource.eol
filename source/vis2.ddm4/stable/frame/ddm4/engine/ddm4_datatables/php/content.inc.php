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
	case 'search':
		if ($this->getCounter($ddm_group, 'search_elements')>0) {
			osW_Settings::getInstance()->setAction('search');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'dosearch':
		if ($this->getCounter($ddm_group, 'search_elements')>0) {
			osW_Settings::getInstance()->setAction('dosearch');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'add':
		if ($this->getCounter($ddm_group, 'add_elements')>0) {
			osW_Settings::getInstance()->setAction('add');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'doadd':
		if ($this->getCounter($ddm_group, 'add_elements')>0) {
			osW_Settings::getInstance()->setAction('doadd');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'edit':
		if ($this->getCounter($ddm_group, 'edit_elements')>0) {
			osW_Settings::getInstance()->setAction('edit');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		if ($this->setLock($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database')))!==true) {
			foreach ($this->getEditElements($ddm_group) as $element => $element_details) {
				$element_details['options']['read_only']=true;
				$this->setReadOnly($ddm_group, $element);
			}
			osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
});
');



		}
		break;
	case 'doedit':
		if ($this->getCounter($ddm_group, 'edit_elements')>0) {
			osW_Settings::getInstance()->setAction('doedit');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		if ($this->setLock($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database')))!==true) {
			osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.ddm4datatables.ajax.reload(null, false);
	window.parent.$(".modal").modal("hide");
	window.parent.vis2_notify("'.addslashes(h()->_setText($this->getGroupMessage($ddm_group, 'lock_error'), array('user'=>$this->getLockUser($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database')))))).'", "danger");
});
');
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'delete':
		if ($this->getCounter($ddm_group, 'delete_elements')>0) {
			osW_Settings::getInstance()->setAction('delete');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'dodelete':
		if ($this->getCounter($ddm_group, 'delete_elements')>0) {
			osW_Settings::getInstance()->setAction('dodelete');
			$_POST['modal']=1;
		} else {
			osW_Settings::getInstance()->setAction('');
		}
		break;
	case 'dolock':
		if ($this->setLock($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database')))==true) {
			die(json_encode(array('status'=>'Ok')));
		}
		die(json_encode(array('status'=>'Error')));
		break;
	case 'log':
	case 'log_ajax':
		$titles=array();
		foreach ($this->getEditElements($ddm_group) as $element => $element_details) {
			$titles[$element]=$element_details['title'];
		}

		if (is_array($this->getFinishElementOption($ddm_group, $element, 'titles'))) {
			foreach ($this->getFinishElementOption($ddm_group, $element, 'titles') as $_key => $_value) {
				$titles[$_key]=$_value;
			}
		}

		if ($this->getFinishElementOption($ddm_group, $element, 'group')!='') {
			$group=$this->getFinishElementOption($ddm_group, $element, 'group');
		} else {
			$group=$this->getGroupOption($ddm_group, 'table', 'database');
		}

		$index_key=$this->getGroupOption($ddm_group, 'index', 'database');
		$index_value=h()->_catch($index_key, '', 'gp');
		$this->removeElements($ddm_group, 'list');

		$this->setParameter($ddm_group, 'ddm_order', array());

		$ddm_group_data=$ddm_group;
		$ddm_group=$ddm_group.'_log';
		osW_Template::getInstance()->set('ddm_group', $ddm_group);
		$this->addGroup($ddm_group, array(
			'messages'=>array(
				'data_noresults'=>'Keine Daten vorhanden',
			),
			'general'=>array(
				'engine'=>'ddm4_datatables',
				'cache'=>h()->_catch('ddm_cache', '', 'pg'),
				'elements_per_page'=>30,
				'enable_log'=>true,
			),
			'direct'=>array(
				'module'=>vOut('frame_current_module'),
				'parameters'=>array(
					'vistool'=>osW_VIS2::getInstance()->getTool(),
					'vispage'=>osW_VIS2_Navigation::getInstance()->getPage(),
				),
			),
			'database'=>array(
				'table'=>'ddm4_log',
				'alias'=>'tbl1',
				'index'=>'log_id',
				'index_type'=>'integer',
				'order'=>array(
					'log_value_time_new'=>'desc',
				),
				'order_case'=>array(
					'user_update_user_id'=>osW_VIS2_Manager::getInstance()->getUsers(),
				),
				'filter'=>array(
					array(
						'and'=>array(
							array('key'=>'log_group', 'operator'=>'=', 'value'=>'\''.$group.'\''),
							array('key'=>'name_index', 'operator'=>'=', 'value'=>'\''.$index_key.'\''),
							array('key'=>'value_index', 'operator'=>'=', 'value'=>$index_value),
						),
					),
				),
			),
		));

		$this->addViewElement($ddm_group, 'datatables', array(
			'module'=>'datatables',
		));

		// DataList

		$this->addDataElement($ddm_group, 'log_key', array(
			'module'=>'select',
			'title'=>'Feld',
			'name'=>'log_key',
			'options'=>array(
				'data'=>$titles,
			),
		));

		$this->addDataElement($ddm_group, 'log_value_time_new', array(
			'module'=>'timestamp',
			'title'=>'Datum (Neu)',
			'name'=>'log_value_time_new',
			'options'=>array(
				'order'=>true,
			),
		));

		$this->addDataElement($ddm_group, 'log_value_time_old', array(
			'module'=>'timestamp',
			'title'=>'Datum (Alt)',
			'name'=>'log_value_time_old',
			'options'=>array(
				'order'=>true,
			),
		));

		$this->addDataElement($ddm_group, 'log_value_user_id_new', array(
			'module'=>'select',
			'title'=>'Benutzer (Neu)',
			'name'=>'log_value_user_id_new',
			'options'=>array(
				'search'=>true,
				'data'=>osW_VIS2_Manager::getInstance()->getUsers(),
			),
		));

		$this->addDataElement($ddm_group, 'log_value_user_id_old', array(
			'module'=>'select',
			'title'=>'Benutzer (Alt)',
			'name'=>'log_value_user_id_old',
			'options'=>array(
				'search'=>true,
				'data'=>osW_VIS2_Manager::getInstance()->getUsers(),
			),
		));

		$this->addDataElement($ddm_group, 'log_value_new', array(
			'module'=>'text',
			'title'=>'Wert (Neu)',
			'name'=>'log_value_new',
			'options'=>array(
				'search'=>true,
			),
		));

		$this->addDataElement($ddm_group, 'log_value_old', array(
			'module'=>'text',
			'title'=>'Wert (Alt)',
			'name'=>'log_value_old',
			'options'=>array(
				'search'=>true,
			),
		));

		$this->addDataElement($ddm_group, 'submit', array(
			'module'=>'submit',
		));

		osW_Settings::getInstance()->setAction(osW_Settings::getInstance()->getAction());
		break;
	case 'ajax':
		osW_Settings::getInstance()->setAction('ajax');
		break;
	default:
		osW_Settings::getInstance()->setAction('');
		break;
}

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', $this->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

// Search
if ((osW_Settings::getInstance()->getAction()=='search')||(osW_Settings::getInstance()->getAction()=='dosearch')) {
	foreach ($this->getSearchElements($ddm_group) as $element => $options) {
		$this->parseFormSearchElementPHP($ddm_group, $element, $options);
		$this->setSearchElementStorage($ddm_group, $element, $this->getSearchElementOption($ddm_group, $element, 'default_value'));
	}

	if (osW_Settings::getInstance()->getAction()=='dosearch') {
		$data=array();
		if (h()->_catch('ddm4_search_delete', '0', 'p')=='0') {
			foreach ($this->getSearchElementsValue($ddm_group, 'name') as $element) {
				$form_data=h()->_catch($element, '', 'pg');
				if (strlen($form_data)>0) {
					$data[$element]=$form_data;
				}
			}
		}
		if ($data==array()) {
			osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.$("#ddm4_button_search_edit").hide();
	window.parent.$("#ddm4_button_search_submit").show();
});
			');
		} else {
			osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.$("#ddm4_button_search_edit").show();
	window.parent.$("#ddm4_button_search_submit").hide();
});
			');
		}

		$this->setParameter($ddm_group, 'ddm_search_data', $data);
		$this->storeParameters($ddm_group);
	}

	$ddm_search_data=$this->getParameter($ddm_group, 'ddm_search_data');
	foreach ($this->getSearchElementsValue($ddm_group, 'name') as $element) {
		if (isset($ddm_search_data[$element])) {
			$this->setSearchElementStorage($ddm_group, $element, $ddm_search_data[$element]);
		}
	}

	if (osW_Settings::getInstance()->getAction()=='dosearch') {
		osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.ddm4datatables.ajax.reload(null, false);
	window.parent.$(".modal").modal("hide");
});
		');
	}
}



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

				osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.ddm4datatables.ajax.reload(null, false);
	window.parent.$(".modal").modal("hide");
});
				');
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
						$this->setEditElementStorage($ddm_group, $element_details['options']['prefix'].$_name, $QloadData->Value($element_details['options']['prefix'].$_name));
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

			osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.ddm4datatables.ajax.reload(null, false);
	window.parent.$(".modal").modal("hide");
});
			');
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

// Ajax
if (in_array(osW_Settings::getInstance()->getAction(), array('', 'ajax', 'log', 'log_ajax'))) {
	$vars=array();
	$_order=array();
	$_search=array();
	$_hidden=array();
	$_columns=array();

	$_elements=$this->getListElements($ddm_group);
	foreach ($_elements as $element => $options) {
		if ((isset($options['name']))&&($options['name']!='')) {
			$vars[]=$options['name'];
		}

		if ((isset($options['options']))&&(isset($options['options']['order']))&&($options['options']['order']==true)) {
			if (isset($options['name'])) {
				$_order[$options['name']]=$options['name'];
			}
		}

		if ((isset($options['options']))&&(isset($options['options']['hidden']))&&($options['options']['hidden']==true)) {
			if (isset($options['name'])) {
				$_hidden[$options['name']]=$options['name'];
			}
		}

		if ((isset($options['options']))&&(isset($options['options']['search']))&&($options['options']['search']==true)) {
			$file=vOut('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/search_pre.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}

		if (isset($options['name_array'])) {
			foreach ($options['name_array'] as $name) {
				if ($options['options']['prefix']!='') {
					$vars[]=$options['options']['prefix'].$name;
				} else {
					$vars[]=$name;
				}
			}
		}
	}
}



// ajax
if (in_array(osW_Settings::getInstance()->getAction(), array('ajax', 'log_ajax'))) {
	// get parameters
	$columns=h()->_catch('columns', '', 'p');
	$order=h()->_catch('order', '', 'p');
	$search=h()->_catch('search', '', 'p');

	// Get order form group-config
	$ddm_order_case_array=$this->getGroupOption($ddm_group, 'order_case', 'database');
	$ddm_order_case_array_new=array();
	if ((is_array($ddm_order_case_array))&&($ddm_order_case_array!=array())) {
		foreach ($ddm_order_case_array as $key => $value) {
			$ddm_order_case_array_new[$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$key]=$value;
		}
		$ddm_order_case_array=$ddm_order_case_array_new;
	} else {
		$ddm_order_case_array=array();
	}

	// build order by
	$database_order_array=array();
	$database_order_string='';
	if (($order!=array())&&($order!='')) {
		foreach ($order as $key => $values) {
			if ((isset($columns[$order[$key]['column']]))&&(in_array($columns[$order[$key]['column']]['data'], $vars))) {
				if (isset($ddm_order_case_array[$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$columns[$order[$key]['column']]['data']])) {
					$sql='';
					$sql.='CASE '.$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$columns[$order[$key]['column']]['data'].' ';
					$i=0;
					if ($order[$key]['dir']=='asc') {
						foreach($ddm_order_case_array[$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$columns[$order[$key]['column']]['data']] as $k => $v){
							$i++;
							$sql.='WHEN '.h()->_escapeString($k).' THEN '.$i.' ';
						}
					} else {
						foreach(array_reverse($ddm_order_case_array[$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$columns[$order[$key]['column']]['data']], true) as $k => $v){
							$i++;
							$sql.='WHEN '.h()->_escapeString($k).' THEN '.$i.' ';
						}
					}
					$sql.='END';
					$database_order_array[]=$sql;
				} else {
					$database_order_array[]=$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$columns[$order[$key]['column']]['data'].' '.$order[$key]['dir'];
				}
			}
		}
	}
	if (($database_order_array!='')&&($database_order_array!=array())) {
		$database_order_string=' ORDER BY '.implode(', ', $database_order_array);
	}

	// init search
	$ddm_search_case_array_all=array();
	$ddm_search_filter_case_array_all=array();

	// build quick-search
	$ddm_search_case_array=array();
	if (($search['value']!='')&&($_search!=array())) {
		foreach ($_search as $key) {
			$options=$_elements[$key];
			$file=vOut('settings_abspath').'frame/ddm4/list/'.$options['module'].'/php/search.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}
	if ($ddm_search_case_array!=array()) {
		$ddm_search_case_array_all[]='('.implode(' OR ', $ddm_search_case_array).')';
	}

	// build extended-search
	$ddm_search_case_array=array();
	$data=$this->getParameter($ddm_group, 'ddm_search_data');
	foreach ($this->getSearchElements($ddm_group) as $element => $options) {
		if ((isset($data[$element]))&&(isset($options['validation']))&&(isset($options['validation']['module']))) {
			$file=vOut('settings_abspath').'frame/ddm4/parsersearch/'.$options['validation']['module'].'/php/content.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}
	if ($ddm_search_case_array!=array()) {
		$ddm_search_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
	}

	// build filter
	$ddm_search_case_array=array();
	$ddm_filter_array=$this->getGroupOption($ddm_group, 'filter', 'database');
	if (($ddm_filter_array!='')&&($ddm_filter_array!=array())) {
		foreach ($ddm_filter_array as $filter) {
			$ar_values=array();
			foreach ($filter as $logic => $elements) {
				foreach ($elements as $element) {
					$ar_values[]=$this->getGroupOption($ddm_group, 'alias', 'database').'.'.$element['key'].$element['operator'].$element['value'];
				}
			}
			$ddm_search_case_array[]='('.implode(' '.strtoupper($logic).' ', $ar_values).')';
		}
	}
	if ($ddm_search_case_array!=array()) {
		$ddm_search_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
		$ddm_search_filter_case_array_all[]='('.implode(' AND ', $ddm_search_case_array).')';
	}

	// build select-query
	$database_search_string='1';
	if ($ddm_search_case_array_all!=array()) {
		$database_search_string='('.implode(' AND ', $ddm_search_case_array_all).')';
	}
	$database_search_filter_string='1';
	if ($ddm_search_filter_case_array_all!=array()) {
		$database_search_filter_string='('.implode(' AND ', $ddm_search_filter_case_array_all).')';
	}

	// load complete list-count
	$QgetDataLimit=osW_Database::getInstance()->query('SELECT :index: FROM :table: AS :alias: WHERE :search_filter:');
	$QgetDataLimit->bindRaw(':vars:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.implode(', '.$this->getGroupOption($ddm_group, 'alias', 'database').'.', $vars));
	$QgetDataLimit->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
	$QgetDataLimit->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
	$QgetDataLimit->bindRaw(':search_filter:', $database_search_filter_string);
	$QgetDataLimit->bindRaw(':index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	$QgetDataLimit->execute();

	// load list
	$QgetData=osW_Database::getInstance()->query('SELECT :index:, :vars: FROM :table: AS :alias: WHERE :search: :order:');
	$QgetData->bindRaw(':index:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	$QgetData->bindRaw(':vars:', $this->getGroupOption($ddm_group, 'alias', 'database').'.'.implode(', '.$this->getGroupOption($ddm_group, 'alias', 'database').'.', $vars));
	$QgetData->bindTable(':table:', $this->getGroupOption($ddm_group, 'table', 'database'));
	$QgetData->bindRaw(':alias:', $this->getGroupOption($ddm_group, 'alias', 'database'));
	$QgetData->bindRaw(':search:', $database_search_string);
	$QgetData->bindRaw(':order:', $database_order_string);
	$QgetData->setPrimaryKey($this->getGroupOption($ddm_group, 'alias', 'database').'.'.$this->getGroupOption($ddm_group, 'index', 'database'));
	$QgetData->bindLimit(h()->_catch('length', 1, 'gp'), ((h()->_catch('start', 0, 'gp')/h()->_catch('length', 1, 'gp'))+1), 5, 'draw');
	$QgetData->execute();

	// build datatables-result
	if ($QgetData->numberOfRows()>0) {
		$this->ddm[$ddm_group]['storage']['view']=array();
		$this->ddm[$ddm_group]['storage']['view']['data']=array();
		while ($QgetData->next()) {
			$this->incCounter($ddm_group, 'storage_view_elements');
			foreach ($this->getListElements($ddm_group) as $element => $options) {
				$view_data=$QgetData->result;
				$file=vOut('settings_abspath').'frame/ddm4/list/'.$options['module'].'/tpl/content.tpl.php';
				if (file_exists($file)) {
					include $file;
				}
				$QgetData->result=$view_data;
			}
			$this->ddm[$ddm_group]['storage']['view']['data'][]=$QgetData->result;
		}
		$this->addParameter($ddm_group, 'ddm_page', $QgetData->limitrows['current_page_number']);
		$this->ddm[$ddm_group]['storage']['view']['limitrows']=$QgetData->limitrows;
		// output datatables-result
		h()->_die(json_encode(array('draw'=>h()->_catch('draw', 1, 'gp'), 'recordsTotal'=>$QgetDataLimit->numberOfRows(), 'recordsFiltered'=>$this->ddm[$ddm_group]['storage']['view']['limitrows']['number_of_rows'], 'data'=>$this->ddm[$ddm_group]['storage']['view']['data'])));
	} else {
		$this->ddm[$ddm_group]['storage']['view']['data']=array();
		$this->ddm[$ddm_group]['storage']['view']['limitrows']=array();
		// output datatables-result
		h()->_die(json_encode(array('draw'=>h()->_catch('draw', 1, 'gp'), 'recordsTotal'=>$QgetDataLimit->numberOfRows(), 'recordsFiltered'=>0, 'data'=>array())));
	}
}


// list
if (in_array(osW_Settings::getInstance()->getAction(), array('', 'log'))) {
	if($this->getPreViewElements($ddm_group)!=array()) {
		foreach ($this->getPreViewElements($ddm_group) as $element => $options) {
			$file=vOut('settings_abspath').'frame/ddm4/view/'.$options['module'].'/php/content.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}

	if($this->getViewElements($ddm_group)!=array()) {
		foreach ($this->getViewElements($ddm_group) as $element => $options) {
			$file=vOut('settings_abspath').'frame/ddm4/view/'.$options['module'].'/php/content.inc.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}

	$config_order=$this->getGroupOption($ddm_group, 'order', 'database');
	$ajax_order=$config_order;
	$ajax_columnDefs=array();
	$i=0;
	$c=count($_columns);
	foreach ($_columns as $element) {
		if ($i==($c-1)) {
			$p=0;
		} else {
			$p=$i+1;
		}
		$ajax_columnDefs[] ='{ "data": "'.$element['name'].'", "responsivePriority": '.$p.', "orderable": '.(($element['order']===true)?'true':'false').', "searchable": '.(($element['search']===true)?'true':'false').', "visible": '.((isset($element['hidden'])&&$element['hidden']===true)?'false':'true').', "targets": '.$i.' }';
		if ((isset($config_order[$element['name']]))&&($element['order']===true)) {
			$config_order[$element['name']]='['.$i.', "'.$config_order[$element['name']].'"]';
			$ajax_order[$element['name']]=true;
		}
		$i++;
	}

	$status_keys=$this->getGroupOption($ddm_group, 'status_keys');
	if (!is_array($status_keys)) {
		$status_keys=array();
	}

	$ajax_statuskeys=array();
	if ($status_keys!=array()) {
		foreach ($status_keys as $_key => $values) {
			foreach ($values as $_value) {
				$ajax_statuskeys[]='if (data.'.$_key.'=="'.$_value['value'].'") {
	$("td", row).addClass("'.$_value['class'].'");
}';
			}
		}

	}

	foreach ($ajax_order as $key => $value) {
		if ($value===true) {
			$ajax_order[$key]=$config_order[$key];
		} else {
			unset($ajax_order[$key]);
		}
	}

	$ajax='ajax';
	$value='';
	$count=$this->getGroupOption($ddm_group, 'elements_per_page', 'general');
	if (osW_Settings::getInstance()->getAction()=='log') {
		$ajax='log_ajax';

		$index_key=$this->getGroupOption($ddm_group_data, 'index', 'database');
		$index_value=h()->_catch($index_key, '', 'gp');
		$value='"'.$index_key.'": \''.$index_value.'\'';
		$count=$this->getGroupOption($ddm_group_data, 'elements_per_page', 'general');
	}

	$count=intval($count);
	if ($count<=10) {
		$count=10;
	} elseif (($count>10)&&($count<=25)) {
		$count=25;
	} elseif (($count>25)&&($count<=50)) {
		$count=50;
	} else {
		$count=100;
	}

	$ajax='$(document).ready(function() {
	 ddm4datatables=$(\'#ddm4_datatables_'.$ddm_group.'\').DataTable({
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"pagingType": \'full_numbers\',
		"iDisplayLength": '.$count.',
		"ajax": {
			"url": \''.osW_Template::getInstance()->buildhrefLink($this->getDirectModule($ddm_group), $this->getDirectParameters($ddm_group)).'\',
			"data": {
				"action":\''.$ajax.'\',
				'.$value.'
			},
			"type": \'POST\',
		},
		"language": {
			"decimal": ",",
			"emptyTable": "'.$this->getGroupMessage($ddm_group, 'data_noresults').'",
			"info": "_START_ bis _END_ von _TOTAL_ Einträgen",
			"infoEmpty": "0 bis 0 von 0 Einträgen",
			"infoFiltered": "(gefiltert von _MAX_ Einträgen)",
			"infoPostFix": "",
			"thousands": ".",
			"lengthMenu": "_MENU_ Einträge anzeigen",
			"loadingRecords": "Wird geladen...",
			"processing": "Bitte warten...",
			"search": "Suche:",
			"zeroRecords": "'.$this->getGroupMessage($ddm_group, 'data_noresults').'",
			"paginate": {
				"first": "Erste",
				"last": "Letzte",
				"next": "Nächste",
				"previous": "Zurück"
			},
			"aria": {
				"sortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
				"sortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
			}
		},
 		"order": ['.implode(",", $ajax_order).'],
		"columnDefs": [
			'.implode(",\n			", $ajax_columnDefs).'
		],
		"rowCallback": function( row, data, index ) {
'.implode("\n			", $ajax_statuskeys).'
		}
	});
});';

	osW_Template::getInstance()->addJSCodeHead($ajax);


	osW_Template::getInstance()->addJSCodeHead('
function openDDM4Dialog_'.$ddm_group.'(elem) {
	$("#ddm4modal_dialog_'.$ddm_group.' .modal-header h5").html($(elem).attr("pageTitle"));
	$("#ddm4modal_dialog_'.$ddm_group.' .modal-body p").html($(elem).attr("pageName"));
	$("#ddm4modal_dialog_'.$ddm_group.'.modal").modal("show");
}');

}

$this->storeParameters($ddm_group);

?>