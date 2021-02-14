<?php

if ($profile_run=='init') {
	$navigation_links[100]=array(
		'navigation_id'=>100,
		'module'=>osW_DDM4::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage(),
		'text'=>'Passwort ändern',
	);
} else {
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'engine', 'ddm4_formular', 'general');
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'table', 'vis2_user', 'database');
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'index', 'user_id', 'database');
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'order',  array('user_name'=>'asc'), 'database');
	osW_DDM4::getInstance()->setGroupOption($ddm_group, 'disable_delete', true);

	$messages=array(
		'send_title'=>'Passwort ändern',
		'send_success_title'=>'Passwort wurde erfolgreich geändert',
		'send_error_title'=>'Passwort konnte nicht geändert werden',
	);

	osW_DDM4::getInstance()->setGroupMessages($ddm_group, osW_DDM4::getInstance()->loadDefaultMessages($messages));

	// FormSend
	osW_DDM4::getInstance()->addSendElement($ddm_group, 'navigation', array(
		'module'=>'navigation',
		'options'=>array(
			'data'=>$navigation_links,
		),
	));

	osW_DDM4::getInstance()->addSendElement($ddm_group, 'user_password', array(
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
	));

	osW_DDM4::getInstance()->addSendElement($ddm_group, 'submit', array(
		'module'=>'submit',
	));


	// FormFinish

	osW_DDM4::getInstance()->addFinishElement($ddm_group, 'vis2_edit_password', array(
		'module'=>'vis2_edit_password',
	));

	osW_DDM4::getInstance()->addAfterFinishElement($ddm_group, 'ddm4_direct', array(
		'module'=>'ddm4_direct',
	));

	osW_DDM4::getInstance()->runDDMPHP($ddm_group);
	osW_Template::getInstance()->set('ddm_group', $ddm_group);
}

?>