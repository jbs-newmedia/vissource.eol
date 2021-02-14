<?php

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/configure.project-dev.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/configure.project-dev.php');
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/configure.project.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/configure.project.php');
}

osW_Template::getInstance()->setIndexFile('index', vOut('frame_default_module'));
osW_Template::getInstance()->clearCSSFileHead();
osW_Template::getInstance()->clearCSSCodeHead();
osW_Template::getInstance()->clearJSFileHead();
osW_Template::getInstance()->clearJSCodeHead();
osW_Template::getInstance()->clearCSSFileBody();
osW_Template::getInstance()->clearCSSCodeBody();
osW_Template::getInstance()->clearJSFileBody();
osW_Template::getInstance()->clearJSCodeBody();
osW_JQuery3::getInstance()->init();
osW_Bootstrap4::getInstance()->init();
osW_FontAwesome5::getInstance()->init();

osW_JQuery3::getInstance()->load();
osW_Bootstrap4::getInstance()->load();
osW_FontAwesome5::getInstance()->load();
osW_JQuery3::getInstance()->loadPlugin('easing');
osW_JQuery3::getInstance()->loadPlugin('easing_compatibility');
osW_Bootstrap4::getInstance()->loadPlugin('sbadmin2', ['theme'=>'vis2']);
osW_Bootstrap4::getInstance()->loadPlugin('select');
osW_Bootstrap4::getInstance()->loadPlugin('datatables');
osW_Bootstrap4::getInstance()->loadPlugin('datatables_responsive');
osW_Bootstrap4::getInstance()->loadPlugin('notify');

osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis2.css');
osW_Template::getInstance()->addJSFileHead('modules/'.vOut('frame_current_module').'/js/vis2.js');

osW_Language::getInstance()->addLanguageVar(vOut('frame_current_module'), vOut('vis2_title'), 'navigation');

osW_VIS2_User::getInstance()->loadUser(osW_Session::getInstance()->value('vis2_user_token'));

if(osW_MessageStack::getInstance()->size('session')) {
	foreach (osW_MessageStack::getInstance()->getClass('session') as $type => $messages) {
		foreach ($messages as $message) {
			osW_Bootstrap4_Notify::getInstance()->notify($message['msg'], $type);
		}
	}
	osW_MessageStack::getInstance()->deleteFromSession();
}

# hookpoint
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_header_pre.inc.php';
if (file_exists($file)) {
	require($file);
}

if (osW_VIS2_User::getInstance()->isLoggedIn()!==true) {
	$request_uri=$_SERVER['REQUEST_URI'];
	if (defined('SID')) {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)/', SID, $request_uri);
	} else {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)(&?)/', '', $request_uri);
	}

	if ((!stristr($request_uri, 'vis2/'.vOut('vis2_login_module').'/'))&&(strlen($request_uri)>strlen('vis2/'.vOut('vis2_login_module').'/'))) {
		$vis2_login_link=h()->_catch('vis2_login_link', '', 's');
		if (strlen($vis2_login_link)==0) {
			osW_Session::getInstance()->set('vis2_login_link', $request_uri);
		}
	}

	osW_VIS2::getInstance()->setTool(vOut('vis2_login_module'));
} else {
	if (osW_VIS2::getInstance()->setTool(h()->_catch('vistool', osW_Session::getInstance()->value('vis2_tool'), 'g'))!==true) {
		osW_VIS2_User::getInstance()->doLogout();
		osW_VIS2::getInstance()->setTool(vOut('vis2_login_module'));
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Programm nicht verfügbar.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool()));
	} elseif (osW_VIS2::getInstance()->getTool()==vOut('vis2_login_module')) {
		osW_VIS2_User::getInstance()->doLogout();
		osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>'Sie wurden erfolgreich abgemeldet.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool()));
	} elseif (osW_VIS2::getInstance()->checkToolPermissionbyEMail(osW_VIS2::getInstance()->getTool(), osW_VIS2_User::getInstance()->getEMail())!==true) {
		osW_VIS2_User::getInstance()->doLogout();
		osW_VIS2::getInstance()->setTool(vOut('vis2_login_module'));
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie haben für dieses Programm keine Rechte.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool()));
	}
}

osW_VIS2_Permission::getInstance()->loadPermissionsByUserId(osW_VIS2_User::getInstance()->getId());

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/php/content.inc.php';
if (file_exists($file)) {
	include($file);
	osW_Template::getInstance()->set('vis2ontent', osW_Template::getInstance()->fetchFileIfExists('content', vOut('frame_current_module').'/vistools/'.osW_VIS2::getInstance()->getTool().'/'));
} else {
	osW_Template::getInstance()->set('vis2ontent', '');
}

if (in_array(osW_VIS2::getInstance()->getTool(), [vOut('vis2_login_module'), vOut('vis2_chtool_module')])) {
	osW_Template::getInstance()->addHeaderData('title', vOut('vis2_title'), true);
} else {
	osW_Template::getInstance()->addHeaderData('title', osW_VIS2_Navigation::getInstance()->getPageName(osW_VIS2_Navigation::getInstance()->getPage()).vOut('vis2_navigation_char').osW_VIS2::getInstance()->getToolName().vOut('vis2_navigation_char').vOut('vis2_title'), true);
}

if (vOut('vis2_support_ddm3')===true) {
	osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/support_ddm3.css');
}

# hookpoint
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_footer_pre.inc.php';
if (file_exists($file)) {
	require($file);
}

osW_Template::getInstance()->addHeaderData('base', array('href'=>osW_Seo::getInstance()->getBaseUrl()), true);

?>