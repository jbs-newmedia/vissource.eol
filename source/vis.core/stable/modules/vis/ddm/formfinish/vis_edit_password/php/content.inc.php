<?php

switch (osW_Settings::getInstance()->getAction()) {
	case 'dosend':
		$QsaveData=osW_Database::getInstance()->query('UPDATE :table_vis_user: SET user_password=:user_password: WHERE user_id=:user_id:');
		$QsaveData->bindTable(':table_vis_user:', 'vis_user');
		$QsaveData->bindCrypt(':user_password:', $this->getFormDataElement($ddm_group, 'user_password'));
		$QsaveData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
		$QsaveData->execute();
		osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>$this->getMessage($ddm_group, 'send_success_title')));
		break;
	default:
		break;
}

?>