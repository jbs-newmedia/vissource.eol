<?php

$emails=osW_VIS_Ticket::getInstance()->getAllUsers('user_id', 'user_email');
foreach (osW_VIS_Ticket::getInstance()->getAllUsers() as $user_id => $user_name) {
	$ar_projects=osW_VIS_Ticket::getInstance()->getProjectsByUserId($user_id, 'project_id', 'project_id');

	$mail=array();
	$mail['null']=array();
	$mail['now']=array();
	$mail['next']=array();
	$mail['future']=array();

	$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket: WHERE user_id=:user_id: AND ticket_enddate>:ticket_enddate_start: AND ticket_enddate<=:ticket_enddate_end: AND project_id IN (:project_id:) AND status_id IN (:status_id:) ORDER BY ticket_enddate ASC, importance_id DESC, ticket_number DESC');
	$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
	$QgetData->bindInt(':user_id:', $user_id);
	$QgetData->bindInt(':ticket_enddate_start:', 0);
	$QgetData->bindInt(':ticket_enddate_end:', date('Ymd'));
	$QgetData->bindRaw(':project_id:', implode(',', $ar_projects));
	$QgetData->bindRaw(':status_id:', implode(',', 	osW_VIS_Ticket::getInstance()->getStatusData(true, false, false, false, false, false, false, 'status_internal_id', 'status_internal_id')+osW_VIS_Ticket::getInstance()->getStatusData(false, false, true, false, false, false, false, 'status_internal_id', 'status_internal_id')+osW_VIS_Ticket::getInstance()->getStatusData(false, false, false, true, false, false, false, 'status_internal_id', 'status_internal_id')));
	$QgetData->execute();
	if ($QgetData->numberOfRows()>0) {
		while ($QgetData->next()) {
			$mail['now'][]=$QgetData->result;
		}
	}

	$QgetData = osW_Database::getInstance()->query('SELECT * FROM :table_vis_ticket: WHERE user_id=:user_id: AND ticket_enddate>:ticket_enddate_start: AND ticket_enddate<=:ticket_enddate_end: AND project_id IN (:project_id:) AND status_id IN (:status_id:) ORDER BY ticket_enddate ASC, importance_id DESC, ticket_number DESC');
	$QgetData->bindTable(':table_vis_ticket:', 'vis_ticket');
	$QgetData->bindInt(':user_id:', $user_id);
	$QgetData->bindInt(':ticket_enddate_start:', date('Ymd'));
	$QgetData->bindInt(':ticket_enddate_end:', date('Ymd'), mktime(date('h'), date('i'), date('s'), date('m'), date('d')+14, date('Y')));
	$QgetData->bindRaw(':project_id:', implode(',', $ar_projects));
	$QgetData->bindRaw(':status_id:', implode(',', 	osW_VIS_Ticket::getInstance()->getStatusData(true, false, false, false, false, false, false, 'status_internal_id', 'status_internal_id')+osW_VIS_Ticket::getInstance()->getStatusData(false, false, true, false, false, false, false, 'status_internal_id', 'status_internal_id')+osW_VIS_Ticket::getInstance()->getStatusData(false, false, false, true, false, false, false, 'status_internal_id', 'status_internal_id')));
	$QgetData->execute();
	if ($QgetData->numberOfRows()>0) {
		while ($QgetData->next()) {
			$mail['next'][]=$QgetData->result;
		}
	}

	if (($mail['now']!=array())||($mail['next']!=array())) {
		$mail['info']='Hier ist eine Übersicht deiner Tickets, die überfällig oder in den nächsten 14 Tagen zu erledigen sind.';
		osW_VIS_Mailer::getInstance()->clearMailer();
		osW_VIS_Mailer::getInstance()->setTool('Ticket', 'VIS');
		if (vOut('vis_ticket_email_from')!='') {
			osW_VIS_Mailer::getInstance()->setFrom(vOut('vis_ticket_email_from'));
		} else {
			osW_VIS_Mailer::getInstance()->setFrom(vOut('project_email'));
		}
		osW_VIS_Mailer::getInstance()->addAddress($emails[$user_id]);
		if (vOut('vis_ticket_email_log')!='') {
			osW_VIS_Mailer::getInstance()->addBCC(vOut('vis_ticket_email_log'));
		}
		osW_VIS_Mailer::getInstance()->setSubject('Übersicht deiner Tickets');
		osW_VIS_Mailer::getInstance()->setContentFile('ticket_cron_report');
		osW_VIS_Mailer::getInstance()->setVar('mail', $mail);
		osW_VIS_Mailer::getInstance()->send();
		osW_VIS_Mailer::getInstance()->clearMailer();
	}
}

?>