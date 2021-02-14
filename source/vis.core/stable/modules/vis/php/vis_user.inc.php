<?php

$ddm_group='vis_user';

osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
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
		'table'=>'vis_user',
		'alias'=>'tbl1',
		'index'=>'user_id',
		'index_type'=>'integer',
		'order'=>array(
			'user_lastname'=>'asc',
			'user_firstname'=>'asc',
			'user_name'=>'asc',
		),
		'order_case'=>array(
			'user_create_time'=>array(
				1=>'bbbb',
				2=>'aaaa',
			),
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'user_id',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_name', array(
	'module'=>'text',
	'title'=>'Benutzername',
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
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_form', array(
	'module'=>'select',
	'title'=>'Anrede',
	'name'=>'user_form',
	'options'=>array(
		'order'=>true,
		'required'=>true,
		'data'=>array('Herr'=>'Herr', 'Frau'=>'Frau'),
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>4,
		'length_max'=>4,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_firstname', array(
	'module'=>'text',
	'title'=>'Vorname',
	'name'=>'user_firstname',
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_lastname', array(
	'module'=>'text',
	'title'=>'Nachname',
	'name'=>'user_lastname',
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_gender', array(
	'module'=>'select',
	'title'=>'Geschlecht',
	'name'=>'user_gender',
	'options'=>array(
		'order'=>true,
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
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_password', array(
	'module'=>'password_double',
	'title'=>'Passwort',
	'name'=>'user_password',
	'options'=>array(
		'required'=>true,
		'title_double'=>'Passwort (wdh)',
	),
	'validation'=>array(
		'module'=>'crypt',
		'length_min'=>vOut('vis_user_password_length_min'),
		'length_max'=>vOut('vis_user_password_length_max'),
		'filter'=>array(
			'password_double'=>array(),
		),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_phone', array(
	'module'=>'text',
	'title'=>'Telefon',
	'name'=>'user_phone',
	'options'=>array(
		'order'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis_user_phone_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_fax', array(
	'module'=>'text',
	'title'=>'Telefax',
	'name'=>'user_fax',
	'options'=>array(
		'order'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis_user_fax_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_mobile', array(
	'module'=>'text',
	'title'=>'Mobile',
	'name'=>'user_mobile',
	'options'=>array(
		'order'=>true,
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>0,
		'length_max'=>32,
		'preg'=>vOut('vis_user_mobile_preg'),
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_status', array(
	'module'=>'yesno',
	'title'=>'Status',
	'name'=>'user_status',
	'options'=>array(
		'order'=>true,
		'default_value'=>1,
		'required'=>true,
		'text_yes'=>'Aktiviert',
		'text_no'=>'Deaktiviert',
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_user_tool', array(
	'module'=>'vis_user_tool',
	'title'=>'Tools',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'vis_user_group', array(
	'module'=>'vis_user_group',
	'title'=>'Gruppen',
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
	'module'=>'createupdatestatus',
	'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
	'options'=>array(
		'prefix'=>'user_',
		'time'=>time(),
		'user_id'=>osW_VIS_User::getInstance()->getId(),
		'order'=>true,
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

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_user_tool_write', array(
	'module'=>'vis_user_tool_write',
));

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_user_group_write', array(
	'module'=>'vis_user_group_write',
));

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_user_delete', array(
	'module'=>'vis_user_delete',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

osW_DDM3::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>