<?php

$ddm_group='vis2_manager_tool';

osW_DDM4::getInstance()->addGroup($ddm_group, array(
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
		'engine'=>'ddm4_datatables',
		'cache'=>h()->_catch('ddm_cache', '', 'pg'),
		'elements_per_page'=>50,
		'enable_log'=>true,
		'status_keys'=>array(
			'tool_status'=>array(
				array('value'=>0,'class'=>'danger'),
			),
		),
	),
	'direct'=>array(
		'module'=>vOut('frame_current_module'),
		'parameters'=>array(
			'vistool'=>osW_VIS2::getInstance()->getTool(),
			'vispage'=>osW_VIS2_Navigation::getInstance()->getPage(),
		),
	),
	'database'=>array(
		'table'=>'vis2_tool',
		'alias'=>'tbl1',
		'index'=>'tool_id',
		'index_type'=>'integer',
		'order'=>array(
			'tool_name_intern'=>'asc',
		),
	),
));


// DataView

osW_DDM4::getInstance()->addViewElement($ddm_group, 'datatables', array(
	'module'=>'datatables',
));

// DataList

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_name', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_name_intern', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_description', array(
	'module'=>'text',
	'title'=>'Beschreibung',
	'name'=>'tool_description',
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>128,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_status', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_hide_logon', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_hide_navigation', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_use_mandant', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'tool_use_mandantswitch', array(
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

osW_DDM4::getInstance()->addDataElement($ddm_group, 'vis2_manager_tool_user', array(
	'module'=>'vis2_manager_tool_user',
	'title'=>'Benutzer',
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'vis2_createupdatestatus', array(
	'module'=>'vis2_createupdatestatus',
	'title'=>osW_DDM4::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
	'options'=>array(
		'prefix'=>'tool_',
		'time'=>time(),
		'user_id'=>osW_VIS2_User::getInstance()->getId(),
		'order'=>true,
		'search'=>true,
	),
	'_list'=>array(
		'options'=>array(
			'display_create_time'=>false,
			'display_create_user'=>false,
		),
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'options', array(
	'module'=>'options',
	'title'=>'Optionen',
));


// FormFinish

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'ddm4_store_form_data', array(
	'module'=>'ddm4_store_form_data',
	'options'=>array(
		'createupdatestatus_prefix'=>'user_',
	),
));

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_manager_tool_user_write', array(
	'module'=>'vis2_manager_tool_user_write',
));

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_manager_tool_delete', array(
	'module'=>'vis2_manager_tool_delete',
));

osW_DDM4::getInstance()->addAfterFinishElement($ddm_group, 'ddm4_direct', array(
	'module'=>'ddm4_direct',
));

osW_DDM4::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>