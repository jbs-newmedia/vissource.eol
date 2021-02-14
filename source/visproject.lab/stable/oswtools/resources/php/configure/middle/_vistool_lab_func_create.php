<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'VISTool-Lab Settings',
);

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	foreach ($this->data['values_post'] as $key => $values) {
		$this->data['values_json'][$key]=$values['value'];
	}

	if ((isset($this->data['values_json']['vis_tool_lab']))&&($this->data['values_json']['vis_tool_lab']==1)) {
		osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));
	}

	$_vis_script=array();
	$_vis_script['tool']=array(
		'tool_name'=>'Lab',
		'tool_name_intern'=>'lab',
		'tool_description'=>'Lab-Tool',
		'tool_status'=>1,
		'tool_hide_logon'=>0,
		'tool_hide_navigation'=>0,
	);
	$_vis_script['group']=array();
	$_vis_script['group'][2]=array(
		'group_name'=>'Lab-Admin',
		'group_name_intern'=>'lab_admin',
		'group_description'=>'Lab-Admin',
		'group_status'=>1,
	);
	$_vis_script['permission']=array();
	$_vis_script['permission'][]=array(
		'permission_flag'=>'link',
		'permission_title'=>'Link anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis_script['permission'][]=array(
		'permission_flag'=>'view',
		'permission_title'=>'Seite anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis_script['navigation']=array();
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>0,
		'navigation_title'=>'Programm',
		'navigation_sortorder'=>10,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'Header Programm',
			'page_name_intern'=>'header_program',
			'page_description'=>'Header Programm',
			'page_ispublic'=>1,
			'permission'=>array('link'),
		),
		'permission'=>array(
			2=>array('link'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>'header_program',
		'navigation_title'=>'Übersicht',
		'navigation_sortorder'=>10,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'Startseite',
			'page_name_intern'=>'start',
			'page_description'=>'Startseite',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			2=>array('link','view'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>0,
		'navigation_title'=>'VIS',
		'navigation_sortorder'=>999,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'Header VIS',
			'page_name_intern'=>'header_vis',
			'page_description'=>'Header VIS',
			'page_ispublic'=>1,
			'permission'=>array('link'),
		),
		'permission'=>array(
			2=>array('link'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis',
		'navigation_title'=>'Navigation',
		'navigation_sortorder'=>10,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Navigation',
			'page_name_intern'=>'vis_navigation',
			'page_description'=>'VIS Navigation',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			2=>array('link','view'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis',
		'navigation_title'=>'Benutzer',
		'navigation_sortorder'=>20,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Benutzer',
			'page_name_intern'=>'vis_user',
			'page_description'=>'VIS Benutzer',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			2=>array('link','view'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis',
		'navigation_title'=>'Passwort ändern',
		'navigation_sortorder'=>30,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Passwort ändern',
			'page_name_intern'=>'vis_editpassword',
			'page_description'=>'VIS Passwort ändern',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			2=>array('link','view'),
		),
	);
	$_vis_script['navigation'][]=array(
		'navigation_parent_id'=>'header_vis',
		'navigation_title'=>'Abmelden',
		'navigation_sortorder'=>40,
		'navigation_ispublic'=>1,
		'page'=>array(
			'page_name'=>'VIS Abmelden',
			'page_name_intern'=>'vis_logout',
			'page_description'=>'VIS Abmelden',
			'page_ispublic'=>1,
			'permission'=>array('link','view'),
		),
		'permission'=>array(
			2=>array('link','view'),
		),
	);

	osW_Tool_VIS::getInstance()->parseScript($_vis_script, $this, 3);

	$this->data['messages'][]='VISTool-Lab configured successfully';
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='VISTool-Lab configured successfully';
}

?>