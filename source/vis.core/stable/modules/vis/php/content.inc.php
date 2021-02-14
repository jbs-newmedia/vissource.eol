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
osW_JQuery2::getInstance()->init();

osW_Language::getInstance()->addLanguageVar('vis', vOut('vis_title'), 'navigation');

osW_VIS_User::getInstance()->loadUser(osW_Session::getInstance()->value('vis_user_token'));

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_header_pre.inc.php';
if (file_exists($file)) {
	require($file);
}

if (osW_VIS_User::getInstance()->isLoggedIn()!==true) {
	$request_uri=$_SERVER['REQUEST_URI'];
	if (defined('SID')) {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)/', SID, $request_uri);
	} else {
		$request_uri=preg_replace('/oswsid=([a-z0-9]+)(&?)/', '', $request_uri);
	}

	if ((!stristr($request_uri, 'vis/'.vOut('vis_login_module').'/'))&&(strlen($request_uri)>strlen('vis/'.vOut('vis_login_module').'/'))) {
		$vis_login_link=h()->_catch('vis_login_link', '', 's');
		if (strlen($vis_login_link)==0) {
			osW_Session::getInstance()->set('vis_login_link', $request_uri);
		}
	}
	osW_VIS::getInstance()->setTool(vOut('vis_login_module'));
} else {
	if (osW_VIS::getInstance()->setTool(h()->_catch('vistool', osW_Session::getInstance()->value('vis_tool'), 'g'))!==true) {
		osW_VIS_User::getInstance()->doLogout();
		osW_VIS::getInstance()->setTool(vOut('vis_login_module'));
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Programm nicht verfügbar.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	} elseif (osW_VIS::getInstance()->getTool()==vOut('vis_login_module')) {
		osW_VIS_User::getInstance()->doLogout();
		osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>'Sie wurden erfolgreich abgemeldet.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	} elseif (osW_VIS::getInstance()->checkToolPermissionbyEMail(osW_VIS::getInstance()->getTool(), osW_VIS_User::getInstance()->getEMail())!==true) {
		osW_VIS_User::getInstance()->doLogout();
		osW_VIS::getInstance()->setTool(vOut('vis_login_module'));
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie haben für dieses Programm keine Rechte.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	}
}

osW_VIS_Permission::getInstance()->loadPermissionsByUserId(osW_VIS_User::getInstance()->getId());

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content.inc.php';
if (file_exists($file)) {
	include($file);
	osW_Template::getInstance()->set('viscontent', osW_Template::getInstance()->fetchFileIfExists('content', vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/'));
} else {
	osW_Template::getInstance()->set('viscontent', '');
}

if (osW_VIS::getInstance()->getTool()==vOut('vis_login_module')) {
	osW_Template::getInstance()->addHeaderData('title', vOut('vis_title'), true);
} else {
	osW_Template::getInstance()->addHeaderData('title', osW_VIS_Navigation::getInstance()->getPageName(osW_VIS_Navigation::getInstance()->getPage()).vOut('vis_navigation_char').osW_VIS::getInstance()->getToolName().vOut('vis_navigation_char').vOut('vis_title'), true);
}

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_footer_pre.inc.php';
if (file_exists($file)) {
	require($file);
}

osW_Template::getInstance()->addHeaderData('base', array('href'=>osW_Seo::getInstance()->getBaseUrl()), true);

?>