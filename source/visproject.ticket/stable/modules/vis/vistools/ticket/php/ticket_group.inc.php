<?php

$view=h()->_catch('view', '', 'gp');

$ticket_data=osW_VIS_Ticket::getInstance()->getTicketDataByTicketId(h()->_catch('ticket_id', 0, 'pg'));
$notice_data=osW_VIS_Ticket::getInstance()->getNoticeById(h()->_catch('notice_id', 0, 'pg'));

if (($ticket_data!=array())&&($view=='ticket')) {
	# Ticket anschauen
	$ddm_group='ddm_group';

	$project_ids=osW_VIS_Ticket::getInstance()->getProjectsByUserId(osW_VIS_User::getInstance()->getId(), 'project_id', 'project_id');

	if (!in_array($ticket_data['project_id'], $project_ids)) {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie können dieses Ticket nicht öffnen, da Sie für das Projekt keine Rechte haben.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	}

	osW_DDM3::getInstance()->addGroup($ddm_group, array(
		'messages'=>array(
			'add_title'=>'Notiz hinzufügen',
			'add_success_title'=>'Notiz wurde erfolgreich hinzufügt',
			'add_error_title'=>'Notiz konnte nicht hinzugefügt werden',
			'edit_title'=>'Notiz bearbeiten',
			'edit_load_error_title'=>'Notiz wurde nicht gefunden',
			'edit_success_title'=>'Notiz wurde erfolgreich bearbeitet',
			'edit_error_title'=>'Notiz konnte nicht bearbeitet werden',
			'delete_title'=>'Notiz löschen',
			'delete_load_error_title'=>'Notiz wurde nicht gefunden',
			'delete_success_title'=>'Notiz wurde erfolgreich gelöscht',
			'delete_error_title'=>'Notiz konnte nicht gelöscht werden',
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
				'view'=>$view,
				'ticket_id'=>$ticket_data['ticket_id'],
			),
		),
		'database'=>array(
			'table'=>'vis_ticket_notice',
			'alias'=>'tbl',
			'index'=>'notice_id',
			'index_type'=>'integer',
			'order'=>array(
				'notice_id'=>'desc',
			),
		),
	));

	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/ticket_core_details.inc.php');

	osW_DDM3::getInstance()->runDDMPHP($ddm_group);
	osW_Template::getInstance()->set('ddm_group', $ddm_group);
} else {
	# Ticket uebersicht
	$ddm_group='ddm_group';

	$var='vis_'.osW_VIS::getInstance()->getTool().'_'.osW_VIS_Navigation::getInstance()->getPage().'_tool_id';

	osW_DDM3::getInstance()->addGroup($ddm_group, array(
		'messages'=>array(
			'add_title'=>'Neues Ticket anlegen',
			'add_success_title'=>'Ticket wurde erfolgreich angelegt',
			'add_error_title'=>'Ticket konnte nicht angelegt werden',
			'edit_title'=>'Ticket editieren',
			'edit_load_error_title'=>'Ticket wurde nicht gefunden',
			'edit_success_title'=>'Ticket wurde erfolgreich editiert',
			'edit_error_title'=>'Ticket konnte nicht editiert werden',
			'delete_title'=>'Ticket löschen',
			'delete_load_error_title'=>'Ticket wurde nicht gefunden',
			'delete_success_title'=>'Ticket wurde erfolgreich gelöscht',
			'delete_error_title'=>'Ticket konnte nicht gelöscht werden',
		),
		'general'=>array(
			'engine'=>'datatable',
			'cache'=>h()->_catch('ddm_cache', '', 'pg'),
			'elements_per_page'=>30,
			'disable_delete'=>true,
		),
		'direct'=>array(
			'module'=>vOut('frame_current_module'),
			'parameters'=>array(
				'vistool'=>osW_VIS::getInstance()->getTool(),
				'vispage'=>osW_VIS_Navigation::getInstance()->getPage(),
			),
		),
		'database'=>array(
			'table'=>'vis_ticket',
			'alias'=>'tbl',
			'index'=>'ticket_id',
			'index_type'=>'integer',
			'order'=>array(
				'ticket_enddate'=>'asc',
				'importance_id'=>'desc',
				'ticket_number'=>'desc',
			),
		),
	));


	// Navigation

	osW_DDM3::getInstance()->readParameters($ddm_group);

	$ddm_navigation_id=intval(h()->_catch('ddm_navigation_id', osW_DDM3::getInstance()->getParameter($ddm_group, 'ddm_navigation_id'), 'pg'));

	if (!in_array($ddm_navigation_id, array(1, 2, 3, 4, 5, 6, 7, 99))) {
		$ddm_navigation_id=99;
	}

	osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);

	$project_ids=osW_VIS_Ticket::getInstance()->getProjectsByUserId(osW_VIS_User::getInstance()->getId(), 'project_id', 'project_id');

	if ($project_ids==array()) {
		$project_ids[-1]=-1;
	}

	$groups=osW_VIS_Ticket::getInstance()->getGroupIdsByUserId(osW_VIS_User::getInstance()->getId());
	if ($groups==array()) {
		$groups=array(-1);
	}

	$getcount=osW_VIS_Ticket::getInstance()->getCounter('group_id IN ('.implode(',', $groups).') AND project_id IN ('.implode(',', $project_ids).')');

	$navigation_links=array();
	$navigation_links[]=array(
		'navigation_id'=>99,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Alle ('.$getcount[99].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>1,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Neue ('.$getcount[1].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>2,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Zuletzt bearbeitet ('.$getcount[2].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>3,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Offen ('.$getcount[3].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>6,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Wartend ('.$getcount[6].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>4,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Zugewiesen ('.$getcount[4].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>7,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Bearbeitung ('.$getcount[7].')',
	);
	$navigation_links[]=array(
		'navigation_id'=>5,
		'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
		'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
		'text'=>'Geschlossen ('.$getcount[5].')',
	);

	if (in_array($ddm_navigation_id, array(1))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'ticket_create_time ', 'operator'=>'>', 'value'=>(time()-(60*60*24*7)))))), 'database');
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order', array('ticket_number'=>'desc'), 'database');
	}

	if (in_array($ddm_navigation_id, array(2))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'ticket_update_time ', 'operator'=>'>', 'value'=>(time()-(60*60*24*7)))))), 'database');
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'order', array('ticket_number'=>'desc'), 'database');
	}

	if (in_array($ddm_navigation_id, array(3))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'status_id ', 'operator'=>'IN', 'value'=>'('.implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataOpen(false, 'status_internal_id', 'status_internal_id'))).')')))), 'database');
	}

	if (in_array($ddm_navigation_id, array(6))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'status_id ', 'operator'=>'IN', 'value'=>'('.implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataWaiting(false, 'status_internal_id', 'status_internal_id'))).')')))), 'database');
	}

	if (in_array($ddm_navigation_id, array(4))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'status_id ', 'operator'=>'IN', 'value'=>'('.implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataAssigned(false, 'status_internal_id', 'status_internal_id'))).')')))), 'database');
	}

	if (in_array($ddm_navigation_id, array(5))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'status_id ', 'operator'=>'IN', 'value'=>'('.implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataInWork(false, 'status_internal_id', 'status_internal_id'))).')')))), 'database');
	}

	if (in_array($ddm_navigation_id, array(7))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')'), array('key'=>'status_id ', 'operator'=>'IN', 'value'=>'('.implode(',', (osW_VIS_Ticket::getInstance()->getStatusDataClosed(false, 'status_internal_id', 'status_internal_id'))).')')))), 'database');
	}

	if (in_array($ddm_navigation_id, array(99))) {
		osW_DDM3::getInstance()->setGroupOption($ddm_group, 'filter', array(array('and'=>array(array('key'=>'group_id ', 'operator'=>'IN ', 'value'=>'('.implode(',', $groups).')'), array('key'=>'project_id ', 'operator'=>'IN', 'value'=>'('.implode(',', $project_ids).')')))), 'database');
	}

	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/ticket_core.inc.php');

	osW_DDM3::getInstance()->runDDMPHP($ddm_group);
	osW_Template::getInstance()->set('ddm_group', $ddm_group);
}

osW_Template::getInstance()->addCSSCodeHead('
	table.table_ddm_form tr.table_ddm_row td.table_ddm_col_title {
		width:150px;
	}
');

osW_Template::getInstance()->set('view', $view);
osW_Template::getInstance()->set('ticket_data', $ticket_data);

?>