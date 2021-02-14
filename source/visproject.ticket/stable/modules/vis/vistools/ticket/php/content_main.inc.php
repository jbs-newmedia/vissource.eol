<?php

if(count(osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array'))>0) {
	$data=array();
	$data['navigation_id']='c1';
	$data['custom']=true;
	$data['tool_id']=osW_VIS::getInstance()->getToolId();
	$data['navigation_parent_id']=0;
	$data['navigation_title']='Tickets nach Projekten';
	$data['navigation_sortorder']=25;
	$data['navigation_ispublic']=1;
	$data['page_id']='p1';
	$data['page_name_intern']='header_ticketprojects';
	$data['page_name']='Tickets nach Projekten';
	$data['page_ispublic']=1;
	$data['navigation_path']='';
	$data['navigation_path_array']=array();
	$data['permission']=array('link');
	osW_VIS_Navigation::getInstance()->addNavigationElement($data);

	$i=1;
	$ar_project=array();
	foreach(osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array') as $project_details) {
		if ((!isset($ar_project[$project_details['project_id']]))||(!isset($ar_project[$project_details['project_parent_id']]))) {
			if ($project_details['project_parent_id']>0) {
				$project_details=osW_VIS_Ticket::getInstance()->getProjectData($project_details['project_parent_id']);
			}

			if (!isset($ar_project[$project_details['project_id']])) {
				$ar_project[$project_details['project_id']]=$project_details['project_id'];

				$i++;
				$data=array();
				$data['navigation_id']='c'.($i);
				$data['custom']=true;
				$data['tool_id']=osW_VIS::getInstance()->getToolId();
				$data['navigation_parent_id']='c1';
				$data['navigation_title']=$project_details['project_name'];
				$data['navigation_sortorder']=$i;
				$data['navigation_ispublic']=1;
				$data['page_id']='p'.$i;
				$data['page_name_intern']='ticket_project_'.$project_details['project_id'];
				$data['page_name']=$project_details['project_name'];
				$data['page_ispublic']=1;
				$data['navigation_path']='';
				$data['navigation_path_array']=array();
				$data['permission']=array('link', 'view');
				osW_VIS_Navigation::getInstance()->addNavigationElement($data);
			}
		}
	}
}

?>