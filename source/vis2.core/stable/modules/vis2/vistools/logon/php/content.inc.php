<?php

if(osW_Settings::getInstance()->getAction()=='dologin') {
	$vis2_login_email=h()->_catch('vis2_login_email', '', 'p');
	$vis2_login_password=h()->_catch('vis2_login_password', '', 'p');

	if (osW_VIS2_User::getInstance()->validateEmail($vis2_login_email)===true) {
		$QreadData = osW_Database::getInstance()->query('SELECT user_id, user_password, user_status FROM :table_vis2_user WHERE user_email=:user_email');
		$QreadData->bindTable(':table_vis2_user', 'vis2_user');
		$QreadData->bindValue(':user_email', $vis2_login_email);
		$QreadData->execute();
		if ($QreadData->numberOfRows()==1) {
			$QreadData->next();

			if (strlen($vis2_login_password)==0) {
				osW_Form::getInstance()->addFormError('vis2_login_password', 'Bitte geben Sie Ihr Passwort ein');
				osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Bitte geben Sie Ihr Passwort ein.'));
			} else {
				if ($QreadData->Value('user_status')==1) {
					$protect=osW_VIS2_Protect::getInstance()->check($QreadData->result['user_id']);
					if ($protect===true) {
						if (osW_VIS2_User::getInstance()->validatePassword($vis2_login_password, $QreadData->Value('user_password'))!==true) {
							osW_Form::getInstance()->addFormError('vis2_login_password', 'Ihr Passwort ist falsch');
							osW_VIS2_Protect::getInstance()->add($QreadData->result['user_id'], osW_VIS2::getInstance()->getToolIdByModule($vis2_login_tool));
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihr Passwort ist falsch.'));
						}
					} else {
						if ($protect==0) {
							osW_Form::getInstance()->addFormError('vis2_login_email', 'Sie sind gesperrt');
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind gesperrt.'));
						} else {
							osW_Form::getInstance()->addFormError('vis2_login_email', 'Sie sind bis zum '.date('d.m.Y H:i:s', $protect).' Uhr gesperrt');
							osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind bis zum '.date('d.m.Y H:i:s', $protect).' Uhr gesperrt.'));
						}
					}
				} elseif ($QreadData->Value('user_status')==0) {
					osW_Form::getInstance()->addFormError('vis2_login_email', 'Sie sind nicht aktiviert');
					osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind nicht aktiviert.'));
				} elseif ($QreadData->Value('user_status')==2) {
					osW_Form::getInstance()->addFormError('vis2_login_email', 'Sie sind blockiert');
					osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie sind blockiert.'));
				}
			}
		} elseif ($QreadData->numberOfRows()>1) {
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Systemfehler'));
		} else {
			osW_Form::getInstance()->addFormError('vis2_login_email', 'Ihre E-Mail-Adresse existiert nicht');
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihre E-Mail-Adresse existiert nicht.'));
		}
	} else {
		osW_Form::getInstance()->addFormError('vis2_login_email', 'Ihre E-Mail-Adresse ist ungültig');
		osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Ihre E-Mail-Adresse ist ungültig.'));
	}

	if (osW_MessageStack::getInstance()->size('form')) {
		osW_Settings::getInstance()->setAction('');
	} else {
		if (osW_VIS2_User::getInstance()->doLogin($vis2_login_email)===true) {
			osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>'Sie wurden erfolgreich eingeloggt.'));

			$protect=osW_VIS2_Protect::getInstance()->clear($QreadData->result['user_id']);

			h()->_setCookie('vis2_login_email', $vis2_login_email, vOut('vis2_login_cookie_lifetime'));
			$vis2_login_link=h()->_catch('vis2_login_link', '', 's');
			if ((strlen($vis2_login_link)>0)&&($vis2_login_link!='/vis2')&&($vis2_login_link!='/vis2/')&&($vis2_login_link!='/vis2/logon/')) {
				osW_Session::getInstance()->remove('vis2_login_link');
				h()->_direct($vis2_login_link);
			} else {
				if (vOut('vis2_login_tool')!='') {
					osW_VIS2::getInstance()->setTool(vOut('vis2_login_tool'));
				} else {
					osW_VIS2::getInstance()->setTool(vOut('vis2_chtool_module'));
				}
				h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_default_module'), 'vistool='.osW_VIS2::getInstance()->getTool()));
			}
		} else {
			osW_Form::getInstance()->addFormError('vis2_login_email', 'Sie konnten nicht eingeloggt werden');
			osW_Form::getInstance()->addFormError('vis2_login_password', 'Sie konnten nicht eingeloggt werden');
			osW_MessageStack::getInstance()->add('form', 'error', array('msg'=>'Sie konnten nicht eingeloggt werden.'));
		}
	}
}

if (isset($_COOKIE['vis2_login_email'])) {
	$_POST['vis2_login_email']=$_COOKIE['vis2_login_email'];
}

?>