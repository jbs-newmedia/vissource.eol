<?php

$ddm_group='vis_manager_tool';

osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
		'createupdate_title'=>'Datensatzinformationen',
		'data_noresults'=>'Keine Programme vorhanden',
		'search_title'=>'Programme durchsuchen',
		'add_title'=>'Neues Programm anlegen',
		'add_success_title'=>'Programm wurde erfolgreich angelegt',
		'add_error_title'=>'Programm konnte nicht angelegt werden',
		'edit_title'=>'Programm editieren',
		'edit_load_error_title'=>'Programm wurde nicht gefunden',
		'edit_success_title'=>'Programm wurde erfolgreich editiert',
		'edit_error_title'=>'Programm konnte nicht editiert werden',
		'delete_title'=>'Programm löschen',
		'delete_load_error_title'=>'Programm wurde nicht gefunden',
		'delete_success_title'=>'Programm wurde erfolgreich gelöscht',
		'delete_error_title'=>'Programm konnte nicht gelöscht werden',
	),
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
		'table'=>'vis_tool',
		'alias'=>'tbl1',
		'index'=>'tool_id',
		'index_type'=>'integer',
		'order'=>array(
			'tool_name_intern'=>'asc',
		),
	),
));


// DataView

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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'tool_id',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_name', array(
	'module'=>'text',
	'title'=>'Name',
	'name'=>'tool_name',
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_name_intern', array(
	'module'=>'text',
	'title'=>'Name Intern',
	'name'=>'tool_name_intern',
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
		'module'=>'autovalue',
		'options'=>array(
			'required'=>false,
			'notice'=>''
		),
	),
	'_delete'=>array(
		'module'=>'autovalue',
		'options'=>array(
			'required'=>false,
			'notice'=>''
		),
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_description', array(
	'module'=>'text',
	'title'=>'Beschreibung',
	'name'=>'tool_description',
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>128,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_status', array(
	'module'=>'yesno',
	'title'=>'Status',
	'name'=>'tool_status',
	'options'=>array(
		'order'=>true,
		'default_value'=>1,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_hide_logon', array(
	'module'=>'yesno',
	'title'=>'Ausblenden (Login)',
	'name'=>'tool_hide_logon',
	'options'=>array(
		'order'=>true,
		'default_value'=>0,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_hide_navigation', array(
	'module'=>'yesno',
	'title'=>'Ausblenden (Navigation)',
	'name'=>'tool_hide_navigation',
	'options'=>array(
		'order'=>true,
		'default_value'=>0,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_use_mandant', array(
	'module'=>'yesno',
	'title'=>'Mandanten',
	'name'=>'tool_use_mandant',
	'options'=>array(
		'order'=>true,
		'default_value'=>0,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'tool_use_mandantswitch', array(
	'module'=>'yesno',
	'title'=>'Mandanten-Switch',
	'name'=>'tool_use_mandantswitch',
	'options'=>array(
		'order'=>true,
		'default_value'=>0,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_manager_tool_user', array(
	'module'=>'vis_manager_tool_user',
	'title'=>'Benutzer',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
	'module'=>'createupdatestatus',
	'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
	'options'=>array(
		'prefix'=>'tool_',
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

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_manager_tool_user_write', array(
	'module'=>'vis_manager_tool_user_write',
));

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_manager_tool_delete', array(
	'module'=>'vis_manager_tool_delete',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

osW_DDM3::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>