<?php

$ddm_group='ddm_group';

$var='vis_'.osW_VIS::getInstance()->getTool().'_'.osW_VIS_Navigation::getInstance()->getPage().'_tool_id';

$navigation_links=array();
$navigation_links[]=array(
	'navigation_id'=>1,
	'module'=>osW_DDM3::getInstance()->getDirectModule($ddm_group),
	'parameter'=>'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
	'text'=>'Benachrichtigungen',
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

if (!in_array($ddm_navigation_id, array(1))) {
	$ddm_navigation_id=1;
}

osW_DDM3::getInstance()->addParameter($ddm_group, 'ddm_navigation_id', $ddm_navigation_id);


// Benachrichtigungen
if (in_array($ddm_navigation_id, array(1))) {
	$ar_notify_project=array();
	$ar_notify_project_sub=array();
	$ar_notify_project_ids=array();
	$ar_notify_project_sub_ids=array();
	foreach (osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array') as $project_id => $project_element) {
		$ar_notify_project[$project_id]=0;
		$ar_notify_project_ids[]=$project_id;

		if ($project_element['project_parent_id']==0) {
			$ar_notify_project_sub[$project_id]=0;
			$ar_notify_project_sub_ids[]=$project_id;
		}

	}
	$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify: WHERE user_id=:user_id: AND notify_sub=:notify_sub:');
	$QloadData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
	$QloadData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
	$QloadData->bindInt(':notify_sub:', 0);
	$QloadData->execute();
	if ($QloadData->numberOfRows()>0) {
		while ($QloadData->next()>0) {
			$ar_notify_project[$QloadData->result['project_id']]=1;
		}
	}
	$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify: WHERE user_id=:user_id: AND notify_sub=:notify_sub:');
	$QloadData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
	$QloadData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
	$QloadData->bindInt(':notify_sub:', 1);
	$QloadData->execute();
	if ($QloadData->numberOfRows()>0) {
		while ($QloadData->next()>0) {
			$ar_notify_project_sub[$QloadData->result['project_id']]=1;
		}
	}

	if (osW_Settings::getInstance()->getAction()=='dosave') {
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_notify: WHERE project_id NOT IN (:project_ids:) AND user_id=:user_id: AND notify_sub=:notify_sub:');
		$QdeleteData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
		$QdeleteData->bindRaw(':project_ids:', implode(',', $ar_notify_project_ids));
		$QdeleteData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
		$QdeleteData->bindInt(':notify_sub:', 0);
		$QdeleteData->execute();

		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_notify: WHERE project_id NOT IN (:project_ids:) AND user_id=:user_id: AND notify_sub=:notify_sub:');
		$QdeleteData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
		$QdeleteData->bindRaw(':project_ids:', implode(',', $ar_notify_project_sub_ids));
		$QdeleteData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
		$QdeleteData->bindInt(':notify_sub:', 1);
		$QdeleteData->execute();

		$time=time();

		foreach (osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array') as $project_id => $project_element) {
			if ((isset($_POST['project_'.$project_id]))&&($_POST['project_'.$project_id]==1)&&($ar_notify_project[$project_id]==0)) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_ticket_notify: (project_id, notify_sub, user_id, notiz_create_user_id, notiz_create_time, notiz_update_user_id, notiz_update_time) VALUES (:project_id:, :notify_sub:, :user_id:, :notiz_create_user_id:, :notiz_create_time:, :notiz_update_user_id:, :notiz_update_time:)');
				$QinsertData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
				$QinsertData->bindInt(':project_id:', $project_id);
				$QinsertData->bindInt(':notify_sub:', 0);
				$QinsertData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_create_user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_create_time:', $time);
				$QinsertData->bindInt(':notiz_update_user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_update_time:', $time);
				$QinsertData->execute();
			} elseif (((!isset($_POST['project_'.$project_id]))||($_POST['project_'.$project_id]==0))&&($ar_notify_project[$project_id]==1)) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_notify: WHERE project_id=:project_id: AND notify_sub=:notify_sub: AND user_id=:user_id:');
				$QdeleteData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
				$QdeleteData->bindInt(':project_id:', $project_id);
				$QdeleteData->bindInt(':notify_sub:', 0);
				$QdeleteData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
				$QdeleteData->execute();
			}
		}

		foreach (osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array') as $project_id => $project_element) {
			if ((isset($_POST['project_sub_'.$project_id]))&&($_POST['project_sub_'.$project_id]==1)&&($ar_notify_project_sub[$project_id]==0)) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_ticket_notify: (project_id, notify_sub, user_id, notiz_create_user_id, notiz_create_time, notiz_update_user_id, notiz_update_time) VALUES (:project_id:, :notify_sub:, :user_id:, :notiz_create_user_id:, :notiz_create_time:, :notiz_update_user_id:, :notiz_update_time:)');
				$QinsertData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
				$QinsertData->bindInt(':project_id:', $project_id);
				$QinsertData->bindInt(':notify_sub:', 1);
				$QinsertData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_create_user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_create_time:', $time);
				$QinsertData->bindInt(':notiz_update_user_id:', osW_VIS_User::getInstance()->getId());
				$QinsertData->bindInt(':notiz_update_time:', $time);
				$QinsertData->execute();
			} elseif (((!isset($_POST['project_sub_'.$project_id]))||($_POST['project_sub_'.$project_id]==0))&&($ar_notify_project_sub[$project_id]==1)) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_notify: WHERE project_id=:project_id: AND notify_sub=:notify_sub: AND user_id=:user_id:');
				$QdeleteData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
				$QdeleteData->bindInt(':project_id:', $project_id);
				$QdeleteData->bindInt(':notify_sub:', 1);
				$QdeleteData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
				$QdeleteData->execute();
			}
		}
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'ddm_navigation_id='.$ddm_navigation_id.'&vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage()));
	}

	osW_Template::getInstance()->set('ar_notify_project', $ar_notify_project);
	osW_Template::getInstance()->set('ar_notify_project_sub', $ar_notify_project_sub);

}


osW_Template::getInstance()->set('ddm_navigation_id', $ddm_navigation_id);

?>