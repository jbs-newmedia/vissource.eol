<?php

$QreadData=osW_Database::getInstance()->query('SELECT COUNT(notice_id) AS count FROM :table: WHERE ticket_id=:ticket_id:');
$QreadData->bindTable(':table:', 'vis_ticket_notice');
$QreadData->bindInt(':ticket_id:', h()->_catch('ticket_id', 0, 'pg'));
$QreadData->execute();
$QreadData->next();

$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET ticket_notice_count=:ticket_notice_count: WHERE ticket_id=:ticket_id:');
$QupdateData->bindTable(':table:', 'vis_ticket');
$QupdateData->bindInt(':ticket_notice_count:', $QreadData->result['count']);
$QupdateData->bindInt(':ticket_id:', h()->_catch('ticket_id', 0, 'pg'));
$QupdateData->execute();

/***************************************************************************************************************/

$ticket_users=osW_VIS_Ticket::getInstance()->getUsers();
$ticket_usersfl=osW_VIS_Ticket::getInstance()->getAllUsers('user_id', 'user_fullnamefl');

osW_VIS_Ticket::getInstance()->clearTicketDataByTicketId(h()->_catch('ticket_id', 0, 'pg'));
$ticket_data=osW_VIS_Ticket::getInstance()->getTicketDataByTicketId(h()->_catch('ticket_id', 0, 'pg'));

osW_VIS_Ticket::getInstance()->clearNoticeById($this->getIndexElementStorage($ddm_group));
$notice_data=osW_VIS_Ticket::getInstance()->getNoticeById($this->getIndexElementStorage($ddm_group));

$data=osW_VIS_Ticket::getInstance()->getStatus(false, false, false);

$notice_data_old=array();
$notice_data_old['notice_id']=$this->getIndexElementStorage($ddm_group);
foreach ($this->getEditElements($ddm_group) as $element => $element_details) {
	if ((isset($element_details['name']))&&($element_details['name']!='')) {
		$notice_data_old[$element]=$this->getEditElementStorage($ddm_group, $element);
	}
}

$notice_data_old['notice_create_time']=$this->getEditElementStorage($ddm_group, 'notice_create_time');
$notice_data_old['notice_create_user_id']=$this->getEditElementStorage($ddm_group, 'notice_create_user_id');
$notice_data_old['notice_update_time']=$this->getEditElementStorage($ddm_group, 'notice_update_time');
$notice_data_old['notice_update_user_id']=$this->getEditElementStorage($ddm_group, 'notice_update_user_id');

for ($i=1;$i<=10;$i++) {
	$notice_data_old['notice_data'.$i.'_name']=$this->getEditElementStorage($ddm_group, 'notice_data'.$i.'_name');
	$notice_data_old['notice_data'.$i.'_type']=$this->getEditElementStorage($ddm_group, 'notice_data'.$i.'_type');
	$notice_data_old['notice_data'.$i.'_size']=$this->getEditElementStorage($ddm_group, 'notice_data'.$i.'_size');
	$notice_data_old['notice_data'.$i.'_md5']=$this->getEditElementStorage($ddm_group, 'notice_data'.$i.'_md5');
}

if ($ticket_data['ticket_enddate']!=0) {
	$ticket_data['ticket_enddate']=substr($ticket_data['ticket_enddate'], 6, 2).'.'.substr($ticket_data['ticket_enddate'], 4, 2).'.'.substr($ticket_data['ticket_enddate'], 0, 4);
} else {
	$ticket_data['ticket_enddate']='Nicht festgelegt';
}

$ticket_data['ticket_project']=osW_VIS_Ticket::getInstance()->getProjectData($ticket_data['project_id'], 'project_name_full');
$ticket_data['ticket_importance']=osW_VIS_Ticket::getInstance()->getImportanceData($ticket_data['importance_id'], 'importance_description');
$ticket_data['ticket_group']=osW_VIS_Ticket::getInstance()->getGroupData($ticket_data['group_id'], 'group_name');
if (($ticket_data['ticket_group']=='')||($ticket_data['ticket_group']==array())) {
	$ticket_data['ticket_group']='---';
}
if (isset($ticket_users[$ticket_data['user_id']])) {
	$ticket_data['ticket_user']=$ticket_users[$ticket_data['user_id']];
} else {
	$ticket_data['ticket_user']='---';
}

if (isset($ticket_users[$ticket_data['ticket_create_user_id']])) {
	$ticket_data['ticket_create_user']=$ticket_usersfl[$ticket_data['ticket_create_user_id']];
} else {
	$ticket_data['ticket_create_user']='';
}
if (isset($ticket_users[$ticket_data['ticket_update_user_id']])) {
	$ticket_data['ticket_update_user']=$ticket_usersfl[$ticket_data['ticket_update_user_id']];
} else {
	$ticket_data['ticket_update_user']='';
}

if (isset($ticket_users[$notice_data['notice_create_user_id']])) {
	$notice_data['notice_create_user']=$ticket_usersfl[$notice_data['notice_create_user_id']];
} else {
	$notice_data['notice_create_user']='';
}
if (isset($ticket_users[$notice_data['notice_update_user_id']])) {
	$notice_data['notice_update_user']=$ticket_usersfl[$notice_data['notice_update_user_id']];
} else {
	$notice_data['notice_update_user']='';
}

$ticket_data['ticket_status']=$data[$ticket_data['status_id']];

$ticket_data['ticket_data']=array();
for($i=1;$i<=10;$i++) {
	if ((isset($ticket_data['ticket_data'.$i.'_md5']))&&($ticket_data['ticket_data'.$i.'_md5']!='')) {
		if ($ticket_data['ticket_data'.$i.'_name']!='') {
			$ticket_data['ticket_data'][$i]=$ticket_data['ticket_data'.$i.'_name'];
		}
	}
}

if ($ticket_data['ticket_data']==array()) {
	$ticket_data['ticket_data']='---';
} else {
	$ticket_data['ticket_data']=implode('<br/>', $ticket_data['ticket_data']);
}

$notice_data['notice_data']=array();
$notice_data_old['notice_data']=array();
for($i=1;$i<=10;$i++) {
	if (((isset($notice_data['notice_data'.$i.'_md5']))&&($notice_data['notice_data'.$i.'_md5']!=''))||((isset($notice_data_old['notice_data'.$i.'_md5']))&&($notice_data_old['notice_data'.$i.'_md5']!=''))) {
		if (($notice_data_old['notice_data'.$i.'_md5']!=$notice_data['notice_data'.$i.'_md5'])) {
			if ($notice_data['notice_data'.$i.'_name']!='') {
				$notice_data['notice_data'][$i]='<span style="color:green;">'.$notice_data['notice_data'.$i.'_name'].'</span>';
			}
			if ($notice_data_old['notice_data'.$i.'_name']!='') {
				$notice_data_old['notice_data'][$i]='<span style="color:red;">'.$notice_data_old['notice_data'.$i.'_name'].'</span>';
			}
		} else {
			if ($notice_data['notice_data'.$i.'_name']!='') {
				$notice_data['notice_data'][$i]=$notice_data['notice_data'.$i.'_name'];
			}
			if ($notice_data_old['notice_data'.$i.'_name']!='') {
				$notice_data_old['notice_data'][$i]=$notice_data_old['notice_data'.$i.'_name'];
			}
		}
	}
}

if ($notice_data['notice_data']==array()) {
	if ($notice_data_old['notice_data']==array()) {
		$notice_data['notice_data']='---';
	} else {
		$notice_data['notice_data']='<span style="color:green;">---</span>';
	}
} else {
	$notice_data['notice_data']=implode('<br/>', $notice_data['notice_data']);
}
if ($notice_data_old['notice_data']==array()) {
	if ($notice_data['notice_data']==array()) {
		$notice_data_old['notice_data']='---';
	} else {
		$notice_data_old['notice_data']='<span style="color:red;">---</span>';
	}
} else {
	$notice_data_old['notice_data']=implode('<br/>', $notice_data_old['notice_data']);
}

if (isset($ticket_users[$notice_data_old['notice_create_user_id']])) {
	$notice_data_old['notice_create_user']=$ticket_users[$notice_data_old['notice_create_user_id']];
} else {
	$notice_data_old['notice_create_user']='';
}
if (isset($ticket_users[$notice_data_old['notice_update_user_id']])) {
	$notice_data_old['notice_update_user']=$ticket_users[$notice_data_old['notice_update_user_id']];
} else {
	$notice_data_old['notice_update_user']='';
}

$ticket_data['ticket_time_planned']=osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data['ticket_time_planned']);
$ticket_data['ticket_time_needed']=osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data['ticket_time_needed']);
$ticket_data_old['ticket_time_planned']=osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data_old['ticket_time_planned']);
$ticket_data_old['ticket_time_needed']=osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data_old['ticket_time_needed']);

$changed=true;

$mail=array();
$mail['ticket_number']=$ticket_data['ticket_number'];
$mail['ticket_id']=$ticket_data['ticket_id'];

$mail['ticket']['ticket_number']=array();
$mail['ticket']['ticket_number']['info']='Ticket';
$mail['ticket']['ticket_number']['current']=$ticket_data['ticket_number'];
$mail['ticket']['ticket_number']['old']='';
$mail['ticket']['ticket_number']['changed']=false;

$mail['ticket']['ticket_project']=array();
$mail['ticket']['ticket_project']['info']='Projekt';
$mail['ticket']['ticket_project']['current']=$ticket_data['ticket_project'];
$mail['ticket']['ticket_project']['old']='';
$mail['ticket']['ticket_project']['changed']=false;

$mail['ticket']['ticket_importance']=array();
$mail['ticket']['ticket_importance']['info']='Priorität';
$mail['ticket']['ticket_importance']['current']=$ticket_data['ticket_importance'];
$mail['ticket']['ticket_importance']['old']='';
$mail['ticket']['ticket_importance']['changed']=false;

$mail['ticket']['ticket_title']=array();
$mail['ticket']['ticket_title']['info']='Betreff';
$mail['ticket']['ticket_title']['current']=$ticket_data['ticket_title'];
$mail['ticket']['ticket_title']['old']='';
$mail['ticket']['ticket_title']['changed']=false;

$mail['ticket']['ticket_description']=array();
$mail['ticket']['ticket_description']['info']='Beschreibung';
$mail['ticket']['ticket_description']['current']=$ticket_data['ticket_description'];
$mail['ticket']['ticket_description']['old']='';
$mail['ticket']['ticket_description']['changed']=false;

$mail['ticket']['ticket_group']=array();
$mail['ticket']['ticket_group']['info']='Gruppe';
$mail['ticket']['ticket_group']['current']=$ticket_data['ticket_group'];
$mail['ticket']['ticket_group']['old']='';
$mail['ticket']['ticket_group']['changed']=false;

$mail['ticket']['ticket_user']=array();
$mail['ticket']['ticket_user']['info']='Benutzer';
$mail['ticket']['ticket_user']['current']=$ticket_data['ticket_user'];
$mail['ticket']['ticket_user']['old']='';
$mail['ticket']['ticket_user']['changed']=false;

$mail['ticket']['ticket_enddate']=array();
$mail['ticket']['ticket_enddate']['info']='Fällig bis';
$mail['ticket']['ticket_enddate']['current']=$ticket_data['ticket_enddate'];
$mail['ticket']['ticket_enddate']['old']='';
$mail['ticket']['ticket_enddate']['changed']=false;

$mail['ticket']['ticket_status']=array();
$mail['ticket']['ticket_status']['info']='Status';
$mail['ticket']['ticket_status']['current']=$ticket_data['ticket_status'];
$mail['ticket']['ticket_status']['old']='';
$mail['ticket']['ticket_status']['changed']=false;

$mail['ticket']['ticket_time_planned']=array();
$mail['ticket']['ticket_time_planned']['info']='Zeit geplant';
$mail['ticket']['ticket_time_planned']['current']=$ticket_data['ticket_time_planned'];
$mail['ticket']['ticket_time_planned']['old']='';
$mail['ticket']['ticket_time_planned']['changed']=false;

$mail['ticket']['ticket_time_needed']=array();
$mail['ticket']['ticket_time_needed']['info']='Zeit benötigt';
$mail['ticket']['ticket_time_needed']['current']=$ticket_data['ticket_time_needed'];
$mail['ticket']['ticket_time_needed']['old']='';
$mail['ticket']['ticket_time_needed']['changed']=false;

$mail['ticket']['ticket_data']=array();
$mail['ticket']['ticket_data']['info']='Dateien';
$mail['ticket']['ticket_data']['current']=$ticket_data['ticket_data'];
$mail['ticket']['ticket_data']['old']='';
$mail['ticket']['ticket_data']['changed']=false;

$mail['ticket']['ticket_create_time']=array();
$mail['ticket']['ticket_create_time']['info']='Erstellt';
$mail['ticket']['ticket_create_time']['current']=date('d.m.Y, H:i', $ticket_data['ticket_create_time']).' Uhr von '.$ticket_data['ticket_create_user'];
$mail['ticket']['ticket_create_time']['old']='';
$mail['ticket']['ticket_create_time']['changed']=false;

$mail['ticket']['ticket_update_time']=array();
$mail['ticket']['ticket_update_time']['info']='Bearbeitet';
$mail['ticket']['ticket_update_time']['current']=date('d.m.Y, H:i', $ticket_data['ticket_update_time']).' Uhr von '.$ticket_data['ticket_update_user'];
$mail['ticket']['ticket_update_time']['old']='';
$mail['ticket']['ticket_update_time']['changed']=false;


$mail['notice']['notice_description']=array();
$mail['notice']['notice_description']['info']='Notiz';
$mail['notice']['notice_description']['current']=$notice_data['notice_description'];
$mail['notice']['notice_description']['old']=$notice_data_old['notice_description'];
$mail['notice']['notice_description']['changed']=false;
if ($notice_data['notice_description']!=$notice_data_old['notice_description']) {
	$changed=true;
	$mail['notice']['notice_description']['changed']=true;
}

$mail['notice']['notice_data']=array();
$mail['notice']['notice_data']['info']='Dateien';
$mail['notice']['notice_data']['current']=$notice_data['notice_data'];
$mail['notice']['notice_data']['old']=$notice_data_old['notice_data'];
$mail['notice']['notice_data']['changed']=false;
if (strip_tags($mail['notice']['notice_data']['current'])!=strip_tags($mail['notice']['notice_data']['old'])) {
	$changed=true;
	$mail['notice']['notice_data']['changed']=true;
}

$mail['notice']['notice_create_time']=array();
$mail['notice']['notice_create_time']['info']='Erstellt';
$mail['notice']['notice_create_time']['current']=date('d.m.Y, H:i', $notice_data['notice_create_time']).' Uhr von '.$notice_data['notice_create_user'];
$mail['notice']['notice_create_time']['old']=date('d.m.Y, H:i', $notice_data_old['notice_create_time']).' Uhr von '.$notice_data_old['notice_create_user'];
$mail['notice']['notice_create_time']['changed']=false;
if ($notice_data['notice_create_time']!=$notice_data_old['notice_create_time']) {
	$changed=true;
	$mail['notice']['notice_create_time']['changed']=true;
}

$mail['notice']['notice_update_time']=array();
$mail['notice']['notice_update_time']['info']='Bearbeitet';
$mail['notice']['notice_update_time']['current']=date('d.m.Y, H:i', $notice_data['notice_update_time']).' Uhr von '.$notice_data['notice_update_user'];
$mail['notice']['notice_update_time']['old']=date('d.m.Y, H:i', $notice_data_old['notice_update_time']).' Uhr von '.$notice_data_old['notice_update_user'];
$mail['notice']['notice_update_time']['changed']=false;
if ($notice_data['notice_update_time']!=$notice_data_old['notice_update_time']) {
	$changed=true;
	$mail['notice']['notice_update_time']['changed']=true;
}

/***************************************************************************************************************/

$adresses=array();
if ($changed===true) {
	$adresses['pool']=array();
	$adresses['user']=array();
	$adresses['group']=array();
	$adresses['default']=array();
	$adresses['notify']=array();
	if ($ticket_data['user_id']>0) {
		$email=osW_VIS_Ticket::getInstance()->getUserMail($ticket_data['user_id']);
		if (!isset($adresses['pool'][md5($email)])) {
			$adresses['pool'][md5($email)]=true;
			$adresses['user'][]=$email;
		}
	} elseif ($ticket_data['group_id']>0) {
		foreach (osW_VIS_Ticket::getInstance()->getGroupMails($ticket_data['group_id']) as $email) {
			if (!isset($adresses['pool'][md5($email)])) {
				$adresses['pool'][md5($email)]=true;
				$adresses['group'][]=$email;
			}
		}
	} else {
		foreach (osW_VIS_Ticket::getInstance()->getDefaultMails($ticket_data['project_id']) as $email) {
			if (!isset($adresses['pool'][md5($email)])) {
				$adresses['pool'][md5($email)]=true;
				$adresses['default'][]=$email;
			}
		}
	}

	$project_data=osW_VIS_Ticket::getInstance()->getProjectData($ticket_data['project_id']);

	if ($project_data['project_parent_id']==0) {
		$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify: WHERE project_id=:project_id: AND notify_sub=:notify_sub:');
		$QgetData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
		$QgetData->bindInt(':project_id:', $project_data['project_id']);
		$QgetData->bindInt(':notify_sub:', 0);
		$QgetData->execute();
		if ($QgetData->numberOfRows()>0) {
			while ($QgetData->next()) {
				$email=osW_VIS_Ticket::getInstance()->getUserMail($QgetData->result['user_id']);
				if (!isset($adresses['pool'][md5($email)])) {
					$adresses['pool'][md5($email)]=true;
					$adresses['notify'][]=$email;
				}
			}
		}
	} else {
		$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify: WHERE project_id=:project_id: AND notify_sub=:notify_sub:');
		$QgetData->bindTable(':table_vis_ticket_notify:', 'vis_ticket_notify');
		$QgetData->bindInt(':project_id:', $project_data['project_parent_id']);
		$QgetData->bindInt(':notify_sub:', 1);
		$QgetData->execute();
		if ($QgetData->numberOfRows()>0) {
			while ($QgetData->next()) {
				$email=osW_VIS_Ticket::getInstance()->getUserMail($QgetData->result['user_id']);
				if (!isset($adresses['pool'][md5($email)])) {
					$adresses['pool'][md5($email)]=true;
					$adresses['notify'][]=$email;
				}
			}
		}
	}


	$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket_notify_ticket: WHERE ticket_id=:ticket_id:');
	$QgetData->bindTable(':table_vis_ticket_notify_ticket:', 'vis_ticket_notify_ticket');
	$QgetData->bindInt(':ticket_id:', $ticket_data['ticket_id']);
	$QgetData->execute();
	if ($QgetData->numberOfRows()>0) {
		while ($QgetData->next()) {
			$email=osW_VIS_Ticket::getInstance()->getUserMail($QgetData->result['user_id']);
			if (!isset($adresses['pool'][md5($email)])) {
				$adresses['pool'][md5($email)]=true;
				$adresses['notify'][]=$email;
			}
		}
	}
}

/***************************************************************************************************************/

if ($adresses!=array()) {
	unset($adresses['pool']);
	foreach ($adresses as $key => $emails) {
		foreach ($emails as $email) {
			switch ($key) {
				case 'user':
					$mail['info']='Bei einem dir zugewiesen Ticket wurde eine Notiz bearbeitet.';
					break;
				case 'group':
					$mail['info']='Bei einem deiner Gruppe zugewiesen Ticket wurde eine Notiz bearbeitet.';
					break;
				case 'default':
					$mail['info']='Bei einem Ticket ohne Zuordnung wurde eine Notiz bearbeitet.';
					break;
				case 'notify':
				default:
					$mail['info']='Bei einem Ticket wurde eine Notiz bearbeitet.';
					break;
			}

			osW_VIS_Mailer::getInstance()->clearMailer();

			if (vOut('vis_ticket_email_from')!='') {
				osW_VIS_Mailer::getInstance()->setFrom(vOut('vis_ticket_email_from'));
			} else {
				osW_VIS_Mailer::getInstance()->setFrom(vOut('project_email'));
			}

			osW_VIS_Mailer::getInstance()->addAddress($email);

			if (vOut('vis_ticket_email_log')!='') {
				osW_VIS_Mailer::getInstance()->addBCC(vOut('vis_ticket_email_log'));
			}
			osW_VIS_Mailer::getInstance()->setSubject('#'.$ticket_data['ticket_number'].': '.$ticket_data['ticket_title'].' ['.$ticket_data['ticket_importance'].'|'.$ticket_data['ticket_status'].']');

			osW_VIS_Mailer::getInstance()->setContentFile('ticket_write');
			osW_VIS_Mailer::getInstance()->setVar('mail', $mail);

			osW_VIS_Mailer::getInstance()->send();

			osW_VIS_Mailer::getInstance()->clearMailer();
		}
	}
}

?>