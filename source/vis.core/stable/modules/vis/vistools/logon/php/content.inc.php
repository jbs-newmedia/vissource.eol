<?php

osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/css/vis.css');

if (osW_VIS_User::getInstance()->isLoggedIn()===true) {
	osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>'Sie sind bereits eingeloggt.'));
	h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
}

$vis_tools=osW_VIS::getInstance()->getTools();

if ((strlen(vOut('vis_login_tool')))&&(isset($vis_tools[vOut('vis_login_tool')]))) {
	$selector=false;
} else {
	$selector=true;
}

osW_Template::getInstance()->addCSSFileHead('modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/css/login.css');

if(osW_Settings::getInstance()->getAction() == 'dologin') {
	$vis_login_email=h()->_catch('vis_login_email', '', 'p');
	$vis_login_password=h()->_catch('vis_login_password', '', 'p');
	if ((strlen(vOut('vis_login_tool')))&&(isset($vis_tools[vOut('vis_login_tool')]))) {
		$vis_login_tool=vOut('vis_login_tool');
	} else {
		$vis_login_tool=h()->_catch('vis_login_tool', '', 'p');
	}

	if (osW_VIS_User::getInstance()->validateEmail($vis_login_email)===true) {
		$QreadData = osW_Database::getInstance()->query('SELECT user_id, user_password, user_status FROM :table_vis_user WHERE user_email=:user_email');
		$QreadData->bindTable(':table_vis_user', 'vis_user');
		$QreadData->bindValue(':user_email', $vis_login_email);
		$QreadData->execute();
		if ($QreadData->numberOfRows()==1) {
			$QreadData->next();

			if (strlen($vis_login_password)==0) {
				osW_Form::getInstance()->addFormError('login_password');
				osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Bitte geben Sie Ihr Passwort ein.'));
			} else {
				if ($QreadData->Value('user_status')==1) {
					$protect=osW_VIS_Protect::getInstance()->check($QreadData->result['user_id']);
					if ($protect===true) {
						if (osW_VIS_User::getInstance()->validatePassword($vis_login_password, $QreadData->Value('user_password'))===true) {
							if (osW_VIS::getInstance()->checkToolPermissionbyEMail($vis_login_tool, $vis_login_email)!==true) {
								osW_Form::getInstance()->addFormError('login_tool');
								osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie haben keine Rechte für dieses Programm.'));
							} elseif (osW_VIS::getInstance()->setTool($vis_login_tool)!==true) {
								osW_Form::getInstance()->addFormError('login_tool');
								osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Das Programm ist nicht verfügbar.'));
							} else {
								$protect=osW_VIS_Protect::getInstance()->clear($QreadData->result['user_id']);
							}
						} else {
							osW_Form::getInstance()->addFormError('login_password');
							osW_VIS_Protect::getInstance()->add($QreadData->result['user_id'], osW_VIS::getInstance()->getToolIdByModule($vis_login_tool));
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihr Passwort ist falsch.'));
						}
					} else {
						osW_Form::getInstance()->addFormError('login_email');
						if ($protect==0) {
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind gesperrt.'));
						} else {
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind bis zum '.date('d.m.Y H:i:s', $protect).' Uhr gesperrt.'));
						}
					}
				} elseif ($QreadData->Value('user_status')==0) {
					osW_Form::getInstance()->addFormError('login_email');
					osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind nicht aktiviert.'));
				} elseif ($QreadData->Value('user_status')==2) {
					osW_Form::getInstance()->addFormError('login_email');
					osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind blockiert'));
				}
			}
		} elseif ($QreadData->numberOfRows()>1) {
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Systemfehler'));
		} else {
			osW_Form::getInstance()->addFormError('login_email');
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihre E-Mail-Adresse existiert nicht.'));
		}
	} else {
		osW_Form::getInstance()->addFormError('login_email');
		osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihre E-Mail-Adresse ist ungültig.'));
	}

	if (osW_MessageStack::getInstance()->size('form')) {
		osW_Settings::getInstance()->setAction('');
	} else {
		if (osW_VIS_User::getInstance()->doLogin($vis_login_email)===true) {
			osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>'Sie wurden erfolgreich eingeloggt.'));

			osW_VIS::getInstance()->setTool($vis_login_tool);

			h()->_setCookie('vis_login_email', $vis_login_email, vOut('vis_login_cookie_lifetime'));
			h()->_setCookie('vis_login_tool', $vis_login_tool, vOut('vis_login_cookie_lifetime'));
			$vis_login_link=h()->_catch('vis_login_link', '', 's');
			if ((strlen($vis_login_link)>0)&&($vis_login_link!='/vis')&&($vis_login_link!='/vis/')) {
				osW_Session::getInstance()->remove('vis_login_link');
				h()->_direct($vis_login_link);
			} else {
				h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
			}
		} else {
			osW_Form::getInstance()->addFormError('login_email');
			osW_Form::getInstance()->addFormError('login_password');
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie konnten nicht eingeloggt werden.'));
		}
	}
}

if (isset($_COOKIE['login_email'])) {
	$_POST['login_email']=$_COOKIE['login_email'];
}

if (isset($_COOKIE['login_tool'])) {
	$_POST['login_tool']=$_COOKIE['login_tool'];
}

osW_Template::getInstance()->set('selector', $selector);

?>