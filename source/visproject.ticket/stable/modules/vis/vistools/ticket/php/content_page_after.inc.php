<?php

if (osW_VIS_Navigation::getInstance()->getPage('ticket_project')=='ticket_project') {
	osW_VIS_Navigation::getInstance()->setPage('ticket_project_'.osW_Session::getInstance()->value('ticket_project_id'));
}

?>