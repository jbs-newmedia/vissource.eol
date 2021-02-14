<?php

// hook header
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_header.inc.php';
if (file_exists($file)) {
	require($file);
}

osW_JQuery2::getInstance()->load();

$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content_header.inc.php';
if (file_exists($file)) {
	require($file);
}

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/configure.project-dev.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/configure.project-dev.php');
} elseif (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/configure.project.php')) {
	require(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/configure.project.php');
}

if (osW_Object::setIsEnabled('osW_VIS_Upgrade')===true) {
	osW_VIS_Upgrade::getInstance()->upgrade();
}


$tool_details=osW_VIS::getInstance()->getToolDetails();
osW_VIS_Navigation::getInstance()->setPage(osW_VIS_Navigation::getInstance()->getDefaultPage());

if ($tool_details['tool_use_mandant']==1) {
	$mandanten=osW_VIS_Mandant::getInstance()->getMandanten();

	$vis_mandant_id=intval(h()->_catch('vis_mandant_id', -1, 'gp'));
	if (($vis_mandant_id>=0)&&(isset($mandanten[$vis_mandant_id]))) {
		osW_Session::getInstance()->set(osW_VIS_Mandant::getInstance()->getSessionMandantName(), $vis_mandant_id);
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage()));
	}
}

// hook main
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_main.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content_main.inc.php';
if (file_exists($file)) {
	require($file);
}

if ($tool_details['tool_use_mandant']==1) {
	osW_Template::getInstance()->set('mandanten', $mandanten);
}

osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis.css');
osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis_tool.css');
osW_Template::getInstance()->addCSSFileHeadIfExists('modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/css/vis_tool.css');

osW_Template::getInstance()->addJSCodeHead('var api_url=\''.osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool().'&vispage=vis_api').'\';');

osW_Template::getInstance()->addJSFileHead('modules/'.vOut('frame_current_module').'/js/vis_tool.js');

if (osW_Object::setIsEnabled('osW_VIS_Notify')===true) {
	osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis_tool_notify.css');
	osW_Template::getInstance()->addJSFileHead('modules/'.vOut('frame_current_module').'/js/vis_tool_notify.js');
}

osW_VIS_Navigation::getInstance()->setPage(h()->_catch('vispage', '', 'g'));

osW_VIS_Navigation::getInstance()->validatePage();

osW_BreadCrumb::getInstance()->add('Desktop', 'current', 'vistool='.osW_VIS::getInstance()->getTool());

// hook page_before
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_page_before.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content_page_before.inc.php';
if (file_exists($file)) {
	require($file);
}

if (file_exists(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/'.osW_VIS_Navigation::getInstance()->getPage().'.inc.php')) {
	$ddm_____data=array();
	$ddm_____data['navigation_id']='vis_api';
	$ddm_____data['custom']=true;
	$ddm_____data['tool_id']=osW_VIS::getInstance()->getToolId();
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
	osW_VIS_Navigation::getInstance()->addNavigationElement($ddm_____data);
	$ddm_____data=array();

	if (osW_VIS_Permission::getInstance()->checkPermission('view')!==true) {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie haben für diesen Bereich keine Rechte.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getDefaultPage()));
	}
	include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/'.osW_VIS_Navigation::getInstance()->getPage().'.inc.php');
	osW_Template::getInstance()->set('viscontent', osW_Template::getInstance()->fetchFileIfExists(osW_VIS_Navigation::getInstance()->getPage(), vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool()));
} else {
	osW_Template::getInstance()->set('viscontent', '');
}

// hook page_after
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_page_after.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content_page_after.inc.php';
if (file_exists($file)) {
	require($file);
}

if (($tool_details['tool_use_mandant']==1)&&($tool_details['tool_use_mandantswitch']==1)) {
	osW_Template::getInstance()->addJSCodeHead('
	$(window).load(function() {
		$("#vis_mandant_id").change(function() {
			if ($("#vis_mandant_id").val()!="'.osW_Session::getInstance()->value('mandant_id').'") {
				url="'.osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage().'&vis_mandant_id=VIS_MANDANT_ID').'";
				url=url.replace("VIS_MANDANT_ID", $("#vis_mandant_id").val());
				window.location.href = url;
			}
		});
	});
');
}

// hook footer
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/php/content_footer.inc.php';
if (file_exists($file)) {
	require($file);
}
$file=vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/php/content_footer.inc.php';
if (file_exists($file)) {
	require($file);
}

if (vOut('vis_navigation_enabled')===false) {
	osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis_tool_print.css');
}

osW_Template::getInstance()->set('tool_details', $tool_details);

osW_BreadCrumb::getInstance()->add(osW_VIS_Navigation::getInstance()->getPageName(osW_VIS_Navigation::getInstance()->getPage()), 'current', 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage());

if (vOut('vis_toolswitch')===true) {
	osW_Template::getInstance()->addJSCodeHead('
$(window).load(function() {
	shift=false;
	$("#vis_tool_id").keydown(function(e) {
		if (e.keyCode==16) {
		   shift=true;
		}
	});

	$("#vis_tool_id").change(function() {
		if ($("#vis_tool_id").val()!="'.osW_VIS::getInstance()->getTool().'") {
			url="'.osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool=VIS_TOOL_SWITCH').'";
			url=url.replace("VIS_TOOL_SWITCH", $("#vis_tool_id").val());
			if (shift==true) {
				window.open(url);
			} else {
				window.location.href = url;
			}
		}
	});
});
');
}

osW_VIS_Navigation::getInstance()->setCurrentNavigationId(osW_VIS_Navigation::getInstance()->getPageIdByName(osW_VIS_Navigation::getInstance()->getPage()));

?>