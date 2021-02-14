<?php

// hook header
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_header.inc.php';
if (file_exists($file)) {
	require($file);
}

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content_header.inc.php';
if (file_exists($file)) {
	require($file);
}

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/configure.project-dev.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/configure.project-dev.php');
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/configure.project.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/configure.project.php');
}

$tool_details=osW_VIS2::getInstance()->getToolDetails();
osW_VIS2_Navigation::getInstance()->setPage(osW_VIS2_Navigation::getInstance()->getDefaultPage());

if ($tool_details['tool_use_mandant']==1) {
	$mandanten=osW_VIS2_Mandant::getInstance()->getMandanten();

	$vis2_mandant_id=intval(h()->_catch('vis2_mandant_id', -1, 'gp'));
	if (($vis2_mandant_id>=0)&&(isset($mandanten[$vis2_mandant_id]))) {
		osW_Session::getInstance()->set(osW_VIS2_Mandant::getInstance()->getSessionMandantName(), $vis2_mandant_id);
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage()));
	}
}

// hook main
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_main.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content_main.inc.php';
if (file_exists($file)) {
	require($file);
}

if ($tool_details['tool_use_mandant']==1) {
	osW_Template::getInstance()->set('mandanten', $mandanten);
}

osW_Template::getInstance()->addJSCodeHead('var api_url=\''.osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage=vis_api').'\';');

if (osW_Object::setIsEnabled('osW_VIS2_Notify')===true) {
	osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis2_tool_notify.css');
	osW_Template::getInstance()->addJSFileHead('modules/'.vOut('frame_current_module').'/js/vis2_tool_notify.js');
}

osW_VIS2_Navigation::getInstance()->setPage(h()->_catch('vispage', '', 'g'));

osW_VIS2_Navigation::getInstance()->validatePage();

osW_BreadCrumb::getInstance()->add('Desktop', 'current', 'vistool='.osW_VIS2::getInstance()->getTool());

// hook page_before
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_page_before.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content_page_before.inc.php';
if (file_exists($file)) {
	require($file);
}
#print_a(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/'.osW_VIS2_Navigation::getInstance()->getPage().'.inc.php');
if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/'.osW_VIS2_Navigation::getInstance()->getPage().'.inc.php')) {
	$ddm_____data=array();
	$ddm_____data['navigation_id']='vis_api';
	$ddm_____data['custom']=true;
	$ddm_____data['tool_id']=osW_VIS2::getInstance()->getToolId();
	$ddm_____data['navigation_parent_id']=0;
	$ddm_____data['navigation_title']='API';
	$ddm_____data['page_id']='vis_api_1';
	$ddm_____data['navigation_sortorder']=1;
	$ddm_____data['navigation_ispublic']=1;
	$ddm_____data['page_name']='vis_api';
	$ddm_____data['page_description']='API';
	$ddm_____data['page_ispublic']=1;
	$ddm_____data['navigation_path']='';
	$ddm_____data['navigation_path_array']=array();
	$ddm_____data['permission']=array('view');
	osW_VIS2_Navigation::getInstance()->addNavigationElement($ddm_____data);
	$ddm_____data=array();

	if (osW_VIS2_Permission::getInstance()->checkPermission('view')!==true) {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie haben für diesen Bereich keine Rechte.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getDefaultPage()));
	}
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/'.osW_VIS2_Navigation::getInstance()->getPage().'.inc.php');
	osW_Template::getInstance()->set('vis2ontent', osW_Template::getInstance()->fetchFileIfExists(osW_VIS2_Navigation::getInstance()->getPage(), vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool()));
} else {
	h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getDefaultPage()));
	osW_Template::getInstance()->set('vis2ontent', '');
}

// hook page_after
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_page_after.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content_page_after.inc.php';
if (file_exists($file)) {
	require($file);
}

// hook footer
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_footer.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content_footer.inc.php';
if (file_exists($file)) {
	require($file);
}

if (vOut('vis2_navigation_enabled')===false) {
#	osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis2_tool_print.css');
}

osW_Template::getInstance()->set('tool_details', $tool_details);

osW_BreadCrumb::getInstance()->add(osW_VIS2_Navigation::getInstance()->getPageName(osW_VIS2_Navigation::getInstance()->getPage()), 'current', 'vistool='.osW_VIS2::getInstance()->getTool().'&vispage='.osW_VIS2_Navigation::getInstance()->getPage());

osW_Template::getInstance()->addJSCodeHead('var session_timeout=\''.osW_Session::getInstance()->getSessionLifetime().'\';');
osW_Template::getInstance()->addJSCodeHead('var session_logout=\''.osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool=logon').'\';');

osW_VIS2_Navigation::getInstance()->setCurrentNavigationId(osW_VIS2_Navigation::getInstance()->getPageIdByName(osW_VIS2_Navigation::getInstance()->getPage()));

?>