<?php

$ddm_group='vis_navigation';

$var='vis_'.osW_VIS::getInstance()->getTool().'_'.osW_VIS_Navigation::getInstance()->getPage().'_tool_id';

$tool_details=osW_VIS::getInstance()->getToolDetails();

$navigation_links=array();
$navigation_links[1]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Rechte',
);

$navigation_links[2]=array(
	'navigation_id'=>2,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Seiten',
);

$navigation_links[3]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Navigation',
);

$navigation_links[4]=array(
	'navigation_id'=>4,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Gruppen',
);
if ($tool_details['tool_use_mandant']==1) {
	$navigation_links[5]=array(
		'navigation_id'=>5,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Mandanten',
	);
}

osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
		'send_title'=>'Tool wählen',
		'send_success_title'=>'Gruppe wurde erfolgreich angelegt',
		'send_error_title'=>'Gruppe konnte nicht angelegt werden',
	),
	'general'=>array(
		'engine'=>'formular',
		'cache'=>h()->_catch('ddm_cache', '', 'pg'),
		'elements_per_page'=>30,
	),
	'direct'=>array(
		'module'=>vOut('frame_current_module'),
		'parameters'=>array(
			'vistool'=>osW_VIS::getInstance()->getTool(),
			'vispage'=>osW_VIS_Navigation::getInstance()->getPage(),
		),
	),
	'database'=>array(
		'table'=>'vis_group',
		'alias'=>'tbl1',
		'index'=>'group_id',
		'index_type'=>'integer',
		'order'=>array(
			'group_name_intern'=>'asc',
		),
	),
));

osW_DDM3::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM3::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=3;
}

osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);


// Rechte
if (in_array($ddm_navigation_id, array(1))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_permission', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'permission_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolId())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('permission_flag'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Rechte vorhanden',
		'search_title'=>'Rechte durchsuchen',
		'add_title'=>'Neues Recht anlegen',
		'add_success_title'=>'Recht wurde erfolgreich angelegt',
		'add_error_title'=>'Recht konnte nicht angelegt werden',
		'edit_title'=>'Recht editieren',
		'edit_load_error_title'=>'Recht wurde nicht gefunden',
		'edit_success_title'=>'Recht wurde erfolgreich editiert',
		'edit_error_title'=>'Recht konnte nicht editiert werden',
		'delete_title'=>'Recht löschen',
		'delete_load_error_title'=>'Recht wurde nicht gefunden',
		'delete_success_title'=>'Recht wurde erfolgreich gelöscht',
		'delete_error_title'=>'Recht konnte nicht gelöscht werden',
	);

	osW_DDM3::getInstance()->setGroupMessages($ddm_group, osW_DDM3::getInstance()->loadDefaultMessages($messages));

	// DataView

	osW_DDM3::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
		'module'=>'add_and_search',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
		'module'=>'sortorder',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
		'module'=>'table_header',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
		'module'=>'table_data',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
		'module'=>'pages',
	));


	// DataList

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
		'module'=>'header',
		'_search'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
		),
		'_add'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
		),
		'_edit'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
		),
		'_delete'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'permission_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'permission_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'permission_flag', array(
		'module'=>'text',
		'title'=>'Flag',
		'name'=>'permission_flag',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>16,
			'filter'=>array(
				'unique_filter'=>array(),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'permission_title', array(
		'module'=>'text',
		'title'=>'Titel',
		'name'=>'permission_title',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>128,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'permission_ispublic', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'permission_ispublic',
		'options'=>array(
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
		'module'=>'hidden',
		'name'=>'tool_id',
		'options'=>array(
			'default_value'=>osW_VIS::getInstance()->getToolId(),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
		'_view'=>array(
			'enabled'=>false,
		),
		'_search'=>array(
			'enabled'=>false,
		),
		'_edit'=>array(
			'enabled'=>false,
		),
		'_delete'=>array(
			'enabled'=>false,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'permission_',
			'time'=>time(),
			'user_id'=>osW_VIS_User::getInstance()->getId(),
		),
		'_list'=>array(
			'options'=>array(
				'display_create_time'=>false,
				'display_create_user'=>false,
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
		'module'=>'options',
		'title'=>'Optionen',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
		'module'=>'store_form_data',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_navigation_permission_delete', array(
		'module'=>'vis_navigation_permission_delete',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));

}

// Seiten
if (in_array($ddm_navigation_id, array(2))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_page', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'page_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolId())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('page_name_intern'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Seiten vorhanden',
		'search_title'=>'Seiten durchsuchen',
		'add_title'=>'Neue Seite anlegen',
		'add_success_title'=>'Seite wurde erfolgreich angelegt',
		'add_error_title'=>'Seite konnte nicht angelegt werden',
		'edit_title'=>'Seite editieren',
		'edit_load_error_title'=>'Seite wurde nicht gefunden',
		'edit_success_title'=>'Seite wurde erfolgreich editiert',
		'edit_error_title'=>'Seite konnte nicht editiert werden',
		'delete_title'=>'Seite löschen',
		'delete_load_error_title'=>'Seite wurde nicht gefunden',
		'delete_success_title'=>'Seite wurde erfolgreich gelöscht',
		'delete_error_title'=>'Seite konnte nicht gelöscht werden',
	);

	osW_DDM3::getInstance()->setGroupMessages($ddm_group, osW_DDM3::getInstance()->loadDefaultMessages($messages));

	// DataView

	osW_DDM3::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
		'module'=>'add_and_search',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
		'module'=>'sortorder',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
		'module'=>'table_header',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
		'module'=>'table_data',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
		'module'=>'pages',
	));


	// DataList

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
		'module'=>'header',
		'_search'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
		),
		'_add'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
		),
		'_edit'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
		),
		'_delete'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'page_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_name', array(
		'module'=>'text',
		'title'=>'Name',
		'name'=>'page_name',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_name_intern', array(
		'module'=>'text',
		'title'=>'Name Intern',
		'name'=>'page_name_intern',
		'options'=>array(
			'order'=>true,
			'required'=>true,
			'notice'=>'Nur a-z und "_". Nach Speichern nicht änderbar.'
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
			'preg'=>'/[a-z_]+/',
			'filter'=>array(
				'unique_filter'=>array(),
			),
		),
		'_edit'=>array(
			'options'=>array(
				'read_only'=>true,
				'required'=>false,
				'notice'=>''
			),
		),
		'_delete'=>array(
			'options'=>array(
				'notice'=>''
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'page_description',
		'validation'=>array(
			'module'=>'string',
			'length_min'=>0,
			'length_max'=>64,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_navigation_pages_permission', array(
		'module'=>'vis_navigation_pages_permission',
		'title'=>'Rechte',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_ispublic', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'page_ispublic',
		'options'=>array(
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
		'module'=>'hidden',
		'name'=>'tool_id',
		'options'=>array(
			'default_value'=>osW_VIS::getInstance()->getToolId(),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
		'_view'=>array(
			'enabled'=>false,
		),
		'_search'=>array(
			'enabled'=>false,
		),
		'_edit'=>array(
			'enabled'=>false,
		),
		'_delete'=>array(
				'enabled'=>false,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'page_',
			'time'=>time(),
			'user_id'=>osW_VIS_User::getInstance()->getId(),
		),
		'_list'=>array(
			'options'=>array(
				'display_create_time'=>false,
				'display_create_user'=>false,
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
		'module'=>'options',
		'title'=>'Optionen',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
		'module'=>'store_form_data',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_navigation_pages_permission_write', array(
		'module'=>'vis_navigation_pages_permission_write',
	));

//	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_navigation_pages_permission_delete', array(
//		'module'=>'vis_navigation_pages_permission_delete',
//	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));

}


// Navigation
if (in_array($ddm_navigation_id, array(3))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'vis_navigationtree');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index_parent', 'navigation_parent_id');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'navigation_level', '3');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_navigation', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'navigation_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolId())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('navigation_sortorder'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Navigation vorhanden',
		'add_title'=>'Neue Navigation anlegen',
		'add_success_title'=>'Navigation wurde erfolgreich angelegt',
		'add_error_title'=>'Navigation konnte nicht angelegt werden',
		'edit_title'=>'Navigation editieren',
		'edit_load_error_title'=>'Navigation wurde nicht gefunden',
		'edit_success_title'=>'Navigation wurde erfolgreich editiert',
		'edit_error_title'=>'Navigation konnte nicht editiert werden',
		'delete_title'=>'Navigation löschen',
		'delete_load_error_title'=>'Navigation wurde nicht gefunden',
		'delete_success_title'=>'Navigation wurde erfolgreich gelöscht',
		'delete_error_title'=>'Navigation konnte nicht gelöscht werden',
	);

	$ar_data=array();
	foreach (osW_VIS_Navigation::getInstance()->getNavigation(0, osW_DDM3::getInstance()->getGroupOption($ddm_group, 'navigation_level'), osW_VIS::getInstance()->getToolId()) as $navigation_element_1) {
		$ar_data[$navigation_element_1['info']['navigation_id']]=$navigation_element_1['info']['navigation_title'];
		foreach ($navigation_element_1['links'] as $navigation_element_2) {
			$ar_data[$navigation_element_2['info']['navigation_id']]=$navigation_element_1['info']['navigation_title'].' > '.osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'form_title_level_icon').' '.$navigation_element_2['info']['navigation_title'];
		}
	}

	osW_DDM3::getInstance()->setGroupMessages($ddm_group, osW_DDM3::getInstance()->loadDefaultMessages($messages));


	// DataList

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
		'module'=>'header',
		'_search'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
		),
		'_add'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
		),
		'_edit'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
		),
		'_delete'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'navigation_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_parent_id', array(
		'module'=>'select',
		'title'=>'Überseite',
		'name'=>'navigation_parent_id',
		'options'=>array(
			'required'=>true,
			'data'=>$ar_data,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>0,
			'length_max'=>11,
			'value_min'=>0,
			'value_max'=>999999,
		),
		'_edit'=>array(
			'validation'=>array(
				'filter'=>array(
					'vis_navigation_check_parent_id'=>array(),
				),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_title', array(
		'module'=>'text',
		'title'=>'Titel',
		'name'=>'navigation_title',
		'options'=>array(
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'page_id', array(
		'module'=>'select',
		'title'=>'Seite',
		'name'=>'page_id',
		'options'=>array(
			'data'=>osW_VIS_Navigation::getInstance()->getPages(osW_VIS::getInstance()->getToolId()),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>0,
			'length_max'=>11,
			'filter'=>array(
				'unique_filter'=>array(),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_sortorder', array(
		'module'=>'text',
		'title'=>'Sortierung',
		'name'=>'navigation_sortorder',
		'validation'=>array(
			'module'=>'string',
			'length_min'=>1,
			'length_max'=>11,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation_ispublic', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'navigation_ispublic',
		'options'=>array(
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
		'module'=>'hidden',
		'name'=>'tool_id',
		'options'=>array(
			'default_value'=>osW_VIS::getInstance()->getToolID(),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
		'_view'=>array(
			'enabled'=>false,
		),
		'_search'=>array(
			'enabled'=>false,
		),
		'_edit'=>array(
			'enabled'=>false,
		),
		'_delete'=>array(
				'enabled'=>false,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'navigation_',
			'time'=>time(),
			'user_id'=>osW_VIS_User::getInstance()->getId(),
		),
		'_list'=>array(
			'options'=>array(
				'display_create_time'=>false,
				'display_create_user'=>false,
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
		'module'=>'options',
		'title'=>'Optionen',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
		'module'=>'store_form_data',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_navigation_delete', array(
		'module'=>'vis_navigation_delete',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));

}

// Gruppen
if (in_array($ddm_navigation_id, array(4))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_group', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'group_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolId())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('group_name_intern'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Gruppen vorhanden',
		'search_title'=>'Gruppen durchsuchen',
		'add_title'=>'Neue Gruppe anlegen',
		'add_success_title'=>'Gruppe wurde erfolgreich angelegt',
		'add_error_title'=>'Gruppe konnte nicht angelegt werden',
		'edit_title'=>'Gruppe editieren',
		'edit_load_error_title'=>'Gruppe wurde nicht gefunden',
		'edit_success_title'=>'Gruppe wurde erfolgreich editiert',
		'edit_error_title'=>'Gruppe konnte nicht editiert werden',
		'delete_title'=>'Gruppe löschen',
		'delete_load_error_title'=>'Gruppe wurde nicht gefunden',
		'delete_success_title'=>'Gruppe wurde erfolgreich gelöscht',
		'delete_error_title'=>'Gruppe konnte nicht gelöscht werden',
	);

	osW_DDM3::getInstance()->setGroupMessages($ddm_group, osW_DDM3::getInstance()->loadDefaultMessages($messages));


	// DataView

	osW_DDM3::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
		'module'=>'add_and_search',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
		'module'=>'sortorder',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
		'module'=>'table_header',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
		'module'=>'table_data',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
		'module'=>'pages',
	));


	// DataList

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
		'module'=>'header',
		'_search'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
		),
		'_add'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
		),
		'_edit'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
		),
		'_delete'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'group_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_name', array(
		'module'=>'text',
		'title'=>'Name',
		'name'=>'group_name',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_name_intern', array(
		'module'=>'text',
		'title'=>'Name Intern',
		'name'=>'group_name_intern',
		'options'=>array(
			'order'=>true,
			'required'=>true,
			'notice'=>'Nur a-z und "_". Nach Speichern nicht änderbar.'
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
			'preg'=>'/[a-z_]+/',
			'filter'=>array(
				'unique'=>array(),
			),
		),
		'_edit'=>array(
			'options'=>array(
				'read_only'=>true,
				'required'=>false,
				'notice'=>''
			),
		),
		'_delete'=>array(
			'options'=>array(
				'notice'=>''
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'group_description',
		'validation'=>array(
			'module'=>'string',
			'length_min'=>0,
			'length_max'=>64,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_group_permission', array(
		'module'=>'vis_group_permission',
		'title'=>'Rechte',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_group_user', array(
		'module'=>'vis_group_user',
		'title'=>'Benutzer',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_status', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'group_status',
		'options'=>array(
			'order'=>true,
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
		'module'=>'hidden',
		'name'=>'tool_id',
		'options'=>array(
			'default_value'=>osW_VIS::getInstance()->getToolID(),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
		'_view'=>array(
			'enabled'=>false,
		),
		'_search'=>array(
			'enabled'=>false,
		),
		'_edit'=>array(
			'enabled'=>false,
		),
		'_delete'=>array(
				'enabled'=>false,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'group_',
			'time'=>time(),
			'user_id'=>osW_VIS_User::getInstance()->getId(),
		),
		'_list'=>array(
			'options'=>array(
				'display_create_time'=>false,
				'display_create_user'=>false,
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
		'module'=>'options',
		'title'=>'Optionen',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
		'module'=>'store_form_data',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_group_permission_write', array(
		'module'=>'vis_group_permission_write',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_group_user_write', array(
		'module'=>'vis_group_user_write',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_group_delete', array(
		'module'=>'vis_group_delete',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));

}

// Mandanten

if (in_array($ddm_navigation_id, array(5))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_mandant', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'mandant_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolID())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('mandant_number'=>'asc'), 'database');

	$messages=array(
		'createupdate_title'=>'Datensatzinformationen',
		'data_noresults'=>'Keine Mandanten vorhanden',
		'search_title'=>'Mandanten durchsuchen',
		'add_title'=>'Neuen Mandant anlegen',
		'add_success_title'=>'Mandant wurde erfolgreich angelegt',
		'add_error_title'=>'Mandant konnte nicht angelegt werden',
		'edit_title'=>'Mandant editieren',
		'edit_load_error_title'=>'Mandant wurde nicht gefunden',
		'edit_success_title'=>'Mandant wurde erfolgreich editiert',
		'edit_error_title'=>'Mandant konnte nicht editiert werden',
		'delete_title'=>'Mandant löschen',
		'delete_load_error_title'=>'Mandant wurde nicht gefunden',
		'delete_success_title'=>'Mandant wurde erfolgreich gelöscht',
		'delete_error_title'=>'Mandant konnte nicht gelöscht werden',
	);

	osW_DDM3::getInstance()->setGroupMessages($ddm_group, osW_DDM3::getInstance()->loadDefaultMessages($messages));

	// DataView

	osW_DDM3::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
		'module'=>'add_and_search',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
		'module'=>'sortorder',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
		'module'=>'table_header',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
		'module'=>'table_data',
	));

	osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
		'module'=>'pages',
	));


	// DataList

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
		'module'=>'header',
		'_search'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
		),
		'_add'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
		),
		'_edit'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
		),
		'_delete'=>array(
			'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'mandant_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_number', array(
		'module'=>'text',
		'title'=>'Nummer',
		'name'=>'mandant_number',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
			'filter'=>array(
				'unique_filter'=>array(),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_name_intern', array(
		'module'=>'text',
		'title'=>'Name Intern',
		'name'=>'mandant_name_intern',
		'options'=>array(
			'order'=>true,
			'required'=>true,
			'notice'=>'Nur a-z und "_". Nach Speichern nicht änderbar.'
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
			'preg'=>'/[a-z_]+/',
			'filter'=>array(
				'unique'=>array(),
			),
		),
		'_edit'=>array(
			'options'=>array(
				'read_only'=>true,
				'required'=>false,
				'notice'=>''
			),
		),
		'_delete'=>array(
			'options'=>array(
				'notice'=>''
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_name', array(
		'module'=>'text',
		'title'=>'Name',
		'name'=>'mandant_name',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>128,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'mandant_description',
		'validation'=>array(
			'module'=>'string',
			'length_min'=>0,
			'length_max'=>64,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'mandant_status', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'mandant_status',
		'options'=>array(
			'order'=>true,
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
	));
	osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
		'module'=>'hidden',
		'name'=>'tool_id',
		'options'=>array(
			'default_value'=>osW_VIS::getInstance()->getToolID(),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
		'_view'=>array(
			'enabled'=>false,
		),
		'_search'=>array(
			'enabled'=>false,
		),
		'_edit'=>array(
			'enabled'=>false,
		),
		'_delete'=>array(
				'enabled'=>false,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'mandant_',
			'time'=>time(),
			'user_id'=>osW_VIS_User::getInstance()->getId(),
		),
		'_list'=>array(
			'options'=>array(
				'display_create_time'=>false,
				'display_create_user'=>false,
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
		'module'=>'options',
		'title'=>'Optionen',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
		'module'=>'store_form_data',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));

}


osW_DDM3::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>