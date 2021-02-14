<?php

$vis2_tools=osW_VIS2::getInstance()->getTools();
$toolsdata=osW_VIS2::getInstance()->getToolsSelectArraybyPermission();
if (count($toolsdata)==1) {
	h()->_setCookie('vis2_login_tool', key($toolsdata), vOut('vis2_login_cookie_lifetime'));
	h()->_direct(osW_Template::getInstance()->buildhrefLink('current', 'vistool='.key($toolsdata)));
}

if(osW_Settings::getInstance()->getAction() == 'dochange') {
	$vis2_login_tool=h()->_catch('vis2_login_tool', '', 'p');
	if ($vis2_login_tool=='') {
		osW_Form::getInstance()->addFormError('vis2_login_tool', 'Bitte wählen Sie ein Programm aus');
		osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Bitte wählen Sie ein Programm aus.'));
	} elseif (osW_VIS2::getInstance()->checkToolPermissionbyEMail($vis2_login_tool, osW_VIS2_User::getInstance()->getEMail())!==true) {
		osW_Form::getInstance()->addFormError('vis2_login_tool', 'Sie haben keine Rechte für dieses Programm');
		osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie haben keine Rechte für dieses Programm.'));
	} elseif (osW_VIS2::getInstance()->setTool($vis2_login_tool)!==true) {
		osW_Form::getInstance()->addFormError('vis2_login_tool', 'Das Programm ist nicht verfügbar');
		osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Das Programm ist nicht verfügbar.'));
	}

	if (osW_MessageStack::getInstance()->size('form')) {
		osW_Settings::getInstance()->setAction('');
	} else {
		osW_VIS2::getInstance()->setTool($vis2_login_tool);
		h()->_setCookie('vis2_login_tool', $vis2_login_tool, vOut('vis2_login_cookie_lifetime'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink('current', 'vistool='.osW_VIS2::getInstance()->getTool()));
	}
}

if (isset($_COOKIE['vis2_login_tool'])) {
	$_POST['vis2_login_tool']=$_COOKIE['vis2_login_tool'];
}

?>