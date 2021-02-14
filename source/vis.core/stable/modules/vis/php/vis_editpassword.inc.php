<?php

$ddm_group='vis_editpassword';

osW_DDM3::getInstance()->addGroup($ddm_group, array(
	'messages'=>array(
		'send_title'=>'Passwort ändern',
		'send_success_title'=>'Passwort wurde erfolgreich geändert',
		'send_error_title'=>'Passwort konnte nicht geändert werden',
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
		'table'=>'vis_user',
		'alias'=>'tbl1',
		'index'=>'user_id',
		'index_type'=>'integer',
		'order'=>array(
			'user_name'=>'asc',
		),
	),
));


// FormSend

osW_DDM3::getInstance()->addSendElement($ddm_group, 'header', array(
	'module'=>'header',
	'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'send_title', 'messages')
));

osW_DDM3::getInstance()->addSendElement($ddm_group, 'user_password', array(
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
));

osW_DDM3::getInstance()->addSendElement($ddm_group, 'submit', array(
	'module'=>'submit',
));


// FormFinish

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_edit_password', array(
	'module'=>'vis_edit_password',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

osW_DDM3::getInstance()->runDDMPHP($ddm_group);
osW_Template::getInstance()->set('ddm_group', $ddm_group);

?>