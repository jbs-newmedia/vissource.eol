<?php

if (isset($_GET['vispage'])) {
	if (preg_match('/ticket_project_([0-9]+)/', $_GET['vispage'], $match)) {
		if (isset($match['1'])) {
			osW_Session::getInstance()->set('ticket_project_id', intval($match['1']));
			osW_VIS_Navigation::getInstance()->setPage('ticket_project');
			osW_VIS_Permission::getInstance()->addPermissionByUserId('ticket_project', 'view');
		}
	}
}

?>