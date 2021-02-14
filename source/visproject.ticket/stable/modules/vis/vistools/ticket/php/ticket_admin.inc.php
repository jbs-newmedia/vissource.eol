<?php

$ddm_group='ddm_group';

$var='vis_'.osW_VIS::getInstance()->getTool().'_'.osW_VIS_Navigation::getInstance()->getPage().'_tool_id';

$navigation_links=array();
$navigation_links[]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Projekte',
);
$navigation_links[]=array(
	'navigation_id'=>2,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Gruppen (Zuordnung)',
);
$navigation_links[]=array(
	'navigation_id'=>3,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Gruppen (Rechte)',
);
$navigation_links[]=array(
	'navigation_id'=>5,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Benutzer',
);
$navigation_links[]=array(
	'navigation_id'=>8,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Status',
);
$navigation_links[]=array(
	'navigation_id'=>9,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Prioritäten',
);



osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'general'=>array(
		'engine'=>'datatable',
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
		'alias'=>'tbl1',
		'index_type'=>'integer',
	),
));


// Navigation

osW_DDM3::getInstance()->readParameters($ddm_group);

$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM3::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

if (!in_array($ddm_navigation_id, array(1,2,3,5,8,9))) {
	$ddm_navigation_id=1;
}

osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);


// Projekte
if (in_array($ddm_navigation_id, array(1))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'vis_ticket_navigationtree');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index_parent', 'project_parent_id');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_ticket_project', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'project_id', 'database');
#	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'tool_id', 'operator'=>'=', 'value'=>osW_VIS::getInstance()->getToolId())))), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('project_sortorder'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Projekte vorhanden',
		'search_title'=>'Projekte durchsuchen',
		'add_title'=>'Neues Projekt anlegen',
		'add_success_title'=>'Projekt wurde erfolgreich angelegt',
		'add_error_title'=>'Projekt konnte nicht angelegt werden',
		'edit_title'=>'Projekt editieren',
		'edit_load_error_title'=>'Projekt wurde nicht gefunden',
		'edit_success_title'=>'Projekt wurde erfolgreich editiert',
		'edit_error_title'=>'Projekt konnte nicht editiert werden',
		'delete_title'=>'Projekt löschen',
		'delete_load_error_title'=>'Projekt wurde nicht gefunden',
		'delete_success_title'=>'Projekt wurde erfolgreich gelöscht',
		'delete_error_title'=>'Projekt konnte nicht gelöscht werden',
	);

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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'project_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_parent_id', array(
		'module'=>'select',
		'title'=>'Hauptprojekt',
		'name'=>'project_parent_id',
		'options'=>array(
			'data'=>osW_VIS_Ticket::getInstance()->getProjectsStructure(0, 'project_id', 'project_name'),
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>0,
			'length_max'=>11,
			'value_min'=>0,
			'value_max'=>999999,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_name', array(
		'module'=>'text',
		'title'=>'Name',
		'name'=>'project_name',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_name_intern', array(
		'module'=>'text',
		'title'=>'Name Intern',
		'name'=>'project_name_intern',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'project_description',
		'validation'=>array(
			'module'=>'string',
			'length_min'=>0,
			'length_max'=>64,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_status', array(
		'module'=>'yesno',
		'title'=>'Status',
		'name'=>'project_status',
		'options'=>array(
			'order'=>true,
			'default_value'=>1,
			'required'=>true,
			'text_yes'=>'Aktiviert',
			'text_no'=>'Deaktiviert',
		),
		'validation'=>array(
			'module'=>'yesno',
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_sortorder', array(
		'module'=>'text',
		'title'=>'Sortierung',
		'name'=>'project_sortorder',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'project_',
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


// Gruppen (Zuordnung)
if (in_array($ddm_navigation_id, array(2))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_ticket_group', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'group_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(), 'database');
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_ticket_group_project', array(
		'module'=>'vis_ticket_group_project',
		'title'=>'Projekte',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_isdefault', array(
		'module'=>'yesno',
		'title'=>'Default',
		'name'=>'group_isdefault',
		'options'=>array(
			'order'=>true,
			'default_value'=>0,
			'required'=>true,
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

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_group_project_write', array(
		'module'=>'vis_ticket_group_project_write',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_group_delete', array(
		'module'=>'vis_ticket_group_delete',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));
}


// Gruppen (Rechte)
if (in_array($ddm_navigation_id, array(3))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_ticket_pemgroup', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'group_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(), 'database');
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_ticket_pemgroup_project', array(
		'module'=>'vis_ticket_pemgroup_project',
		'title'=>'Projekte',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_isdefault', array(
		'module'=>'yesno',
		'title'=>'Default',
		'name'=>'group_isdefault',
		'options'=>array(
			'order'=>true,
			'default_value'=>0,
			'required'=>true,
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

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_pemgroup_project_write', array(
		'module'=>'vis_ticket_pemgroup_project_write',
	));

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_pemgroup_delete', array(
		'module'=>'vis_ticket_pemgroup_delete',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));
}


// Benutzer
if (in_array($ddm_navigation_id, array(5))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_user', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'user_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('user_name'=>'asc'), 'database');

	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'disable_add', true);
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'disable_delete', true);


	$messages=array(
		'data_noresults'=>'Keine Benutzer vorhanden',
		'search_title'=>'Benutzer durchsuchen',
		'add_title'=>'Neuen Benutzer anlegen',
		'add_success_title'=>'Benutzer wurde erfolgreich angelegt',
		'add_error_title'=>'Benutzer konnte nicht angelegt werden',
		'edit_title'=>'Benutzer editieren',
		'edit_load_error_title'=>'Benutzer wurde nicht gefunden',
		'edit_success_title'=>'Benutzer wurde erfolgreich editiert',
		'edit_error_title'=>'Benutzer konnte nicht editiert werden',
		'delete_title'=>'Benutzer löschen',
		'delete_load_error_title'=>'Benutzer wurde nicht gefunden',
		'delete_success_title'=>'Benutzer wurde erfolgreich gelöscht',
		'delete_error_title'=>'Benutzer konnte nicht gelöscht werden',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'user_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_name', array(
		'module'=>'text',
		'title'=>'Name',
		'name'=>'user_name',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
		'_edit'=>array(
			'read_only'=>true,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_email', array(
		'module'=>'text',
		'title'=>'E-Mail',
		'name'=>'user_email',
		'options'=>array(
			'order'=>true,
			'required'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>5,
			'length_max'=>128,
			'filter'=>array(
				'email'=>array(),
				'unique'=>array(),
			),
		),
		'_edit'=>array(
			'read_only'=>true,
		),
	));

	if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='single')) {
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_ticket_user_project', array(
			'module'=>'vis_ticket_user_project',
			'title'=>'Projekte (Rechte)',
		));
	}

	if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='group')) {
		osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_ticket_user_pemgroup', array(
			'module'=>'vis_ticket_user_pemgroup',
			'title'=>'Gruppen (Rechte)',
		));
	}

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_ticket_user_group', array(
		'module'=>'vis_ticket_user_group',
		'title'=>'Gruppen (Zuordnung)',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'user_',
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

	if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='single')) {
		osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_user_project_write', array(
			'module'=>'vis_ticket_user_project_write',
		));
	}

	if ((vOut('vis_ticket_permission_modus')=='both')||(vOut('vis_ticket_permission_modus')=='group')) {
		osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_user_pemgroup_write', array(
			'module'=>'vis_ticket_user_pemgroup_write',
		));
	}

	osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_user_group_write', array(
		'module'=>'vis_ticket_user_group_write',
	));

	osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
		'module'=>'direct',
	));
}


// Status
if (in_array($ddm_navigation_id, array(8))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_ticket_status', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'status_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('status_sortorder'=>'asc', 'status_internal_id'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Status vorhanden',
		'search_title'=>'Status durchsuchen',
		'add_title'=>'Neuen Status anlegen',
		'add_success_title'=>'Status wurde erfolgreich angelegt',
		'add_error_title'=>'Status konnte nicht angelegt werden',
		'edit_title'=>'Status editieren',
		'edit_load_error_title'=>'Status wurde nicht gefunden',
		'edit_success_title'=>'Status wurde erfolgreich editiert',
		'edit_error_title'=>'Status konnte nicht editiert werden',
		'delete_title'=>'Status löschen',
		'delete_load_error_title'=>'Status wurde nicht gefunden',
		'delete_success_title'=>'Status wurde erfolgreich gelöscht',
		'delete_error_title'=>'Status konnte nicht gelöscht werden',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'status_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_internal_id', array(
		'module'=>'text',
		'title'=>'ID',
		'name'=>'status_internal_id',
		'options'=>array(
			'required'=>true,
			'order'=>true,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
			'filter'=>array(
				'unique'=>array(),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'status_description',
		'options'=>array(
			'required'=>true,
			'order'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_sortorder', array(
		'module'=>'text',
		'title'=>'Sortierung',
		'name'=>'status_sortorder',
		'options'=>array(
			'required'=>true,
			'order'=>true,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_flag', array(
		'module'=>'select',
		'title'=>'Optionen',
		'name'=>'status_flag',
		'options'=>array(
			'default_value'=>0,
			'required'=>true,
			'order'=>true,
			'data'=>array(
				'1'=>'Offen',
				'5'=>'Wartend',
				'0'=>'Zugewiesen',
				'7'=>'Bearbeitung',
				'9'=>'Geschlossen',
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'status_',
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


// Prioritaeten
if (in_array($ddm_navigation_id, array(9))) {
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'engine', 'datatable');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'table', 'vis_ticket_importance', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'index', 'importance_id', 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(), 'database');
	osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order',  array('importance_internal_id'=>'asc'), 'database');

	$messages=array(
		'data_noresults'=>'Keine Prioritäten vorhanden',
		'search_title'=>'Prioritäten durchsuchen',
		'add_title'=>'Neue Priorität anlegen',
		'add_success_title'=>'Priorität wurde erfolgreich angelegt',
		'add_error_title'=>'Priorität konnte nicht angelegt werden',
		'edit_title'=>'Priorität editieren',
		'edit_load_error_title'=>'Priorität wurde nicht gefunden',
		'edit_success_title'=>'Priorität wurde erfolgreich editiert',
		'edit_error_title'=>'Priorität konnte nicht editiert werden',
		'delete_title'=>'Priorität löschen',
		'delete_load_error_title'=>'Priorität wurde nicht gefunden',
		'delete_success_title'=>'Priorität wurde erfolgreich gelöscht',
		'delete_error_title'=>'Priorität konnte nicht gelöscht werden',
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

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'importance_id', array(
		'module'=>'index',
		'title'=>'ID',
		'name'=>'importance_id',
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'importance_internal_id', array(
		'module'=>'text',
		'title'=>'ID',
		'name'=>'importance_internal_id',
		'options'=>array(
			'required'=>true,
			'order'=>true,
		),
		'validation'=>array(
			'module'=>'integer',
			'length_min'=>1,
			'length_max'=>11,
			'filter'=>array(
				'unique'=>array(),
			),
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'importance_description', array(
		'module'=>'text',
		'title'=>'Beschreibung',
		'name'=>'importance_description',
		'options'=>array(
			'required'=>true,
			'order'=>true,
		),
		'validation'=>array(
			'module'=>'string',
			'length_min'=>2,
			'length_max'=>32,
		),
	));

	osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
		'module'=>'createupdatestatus',
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
		'options'=>array(
			'prefix'=>'importance_',
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