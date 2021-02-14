<?php

$ddm_group='vis2_user';

osW_DDM4::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
		'createupdate_title'=>'Datensatzinformationen',
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
	),
	'general'=>array(
		'engine'=>'ddm4_datatables',
		'cache'=>h()->_catch('ddm_cache', '', 'pg'),
		'elements_per_page'=>50,
		'enable_log'=>true,
		'status_keys'=>array(
			'user_status'=>array(
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
		'table'=>'vis2_user',
		'alias'=>'tbl1',
		'index'=>'user_id',
		'index_type'=>'integer',
		'order'=>array(
			'user_lastname'=>'asc',
			'user_firstname'=>'asc',
			'user_name'=>'asc',
		),
		'order_case'=>array(
			'user_update_user_id'=>osW_VIS2_Manager::getInstance()->getUsers(),
		),
	),
));

// DataView

osW_DDM4::getInstance()->addViewElement($ddm_group, 'datatables', array(
	'module'=>'datatables',
));

// DataList

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_name', array(
	'module'=>'text',
	'title'=>'Benutzername',
	'name'=>'user_name',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>2,
		'length_max'=>32,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_form', array(
	'module'=>'select',
	'title'=>'Anrede',
	'name'=>'user_form',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		'required'=>true,
		'data'=>array('Herr'=>'Herr', 'Frau'=>'Frau'),
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>4,
		'length_max'=>4,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_firstname', array(
	'module'=>'text',
	'title'=>'Vorname',
	'name'=>'user_firstname',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>2,
		'length_max'=>32,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_lastname', array(
	'module'=>'text',
	'title'=>'Nachname',
	'name'=>'user_lastname',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>2,
		'length_max'=>32,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_gender', array(
	'module'=>'select',
	'title'=>'Geschlecht',
	'name'=>'user_gender',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		'required'=>true,
		'data'=>array('0'=>'Keine Angabe', '1'=>'Männlich', '2'=>'Weiblich'),
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>2,
		'value_min'=>1,
		'value_max'=>2,
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_email', array(
	'module'=>'text',
	'title'=>'E-Mail',
	'name'=>'user_email',
	'options'=>array(
		'order'=>true,
		'required'=>true,
		'search'=>true,
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
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_password', array(
	'module'=>'password_double',
	'title'=>'Passwort',
	'name'=>'user_password',
	'options'=>array(
		'required'=>true,
		'title_double'=>'Passwort (wdh)',
	),
	'validation'=>array(
		'module'=>'crypt',
		'length_min'=>vOut('vis2_user_password_length_min'),
		'length_max'=>vOut('vis2_user_password_length_max'),
		'filter'=>array(
			'password_double'=>array(),
		),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_phone', array(
	'module'=>'text',
	'title'=>'Telefon',
	'name'=>'user_phone',
	'options'=>array(
		'order'=>true,
		'search'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis2_user_phone_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_fax', array(
	'module'=>'text',
	'title'=>'Telefax',
	'name'=>'user_fax',
	'options'=>array(
		'order'=>true,
		'search'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis2_user_fax_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_mobile', array(
	'module'=>'text',
	'title'=>'Mobile',
	'name'=>'user_mobile',
	'options'=>array(
		'order'=>true,
		'search'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis2_user_mobile_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'user_status', array(
	'module'=>'yesno',
	'title'=>'Status',
	'name'=>'user_status',
	'options'=>array(
		'order'=>true,
		'search'=>true,
		#'default_value'=>1,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
	'_list'=>array(
		'module'=>'hidden',
	),
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'vis2_user_tool', array(
	'module'=>'vis2_user_tool',
	'title'=>'Tools',
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'vis2_user_group', array(
	'module'=>'vis2_user_group',
	'title'=>'Gruppen',
));

osW_DDM4::getInstance()->addDataElement($ddm_group, 'vis2_createupdatestatus', array(
	'module'=>'vis2_createupdatestatus',
	'title'=>osW_DDM4::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
	'options'=>array(
		'prefix'=>'user_',
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

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_user_tool_write', array(
	'module'=>'vis2_user_tool_write',
	'options'=>array(
		'createupdatestatus_prefix'=>'user_',
	),
));

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_user_group_write', array(
	'module'=>'vis2_user_group_write',
	'options'=>array(
		'createupdatestatus_prefix'=>'user_',
	),
));

osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_user_delete', array(
	'module'=>'vis2_user_delete',
));

osW_DDM4::getInstance()->addAfterFinishElement($ddm_group, 'ddm4_direct', array(
	'module'=>'ddm4_direct',
));

osW_DDM4::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>