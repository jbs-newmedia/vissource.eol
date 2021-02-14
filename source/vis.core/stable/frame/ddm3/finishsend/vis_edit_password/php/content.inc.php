<?php

/*
 * Author: Juergen Schwind
 * Copyright: Juergen Schwind
 * Link: http://oswframe.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

$vis_time=time();
$vis_user_id=osW_VIS_User::getInstance()->getId();

$QsaveData=osW_Database::getInstance()->query('UPDATE :table_vis_user: SET user_password=:user_password:, user_update_time=:user_update_time:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
$QsaveData->bindTable(':table_vis_user:', 'vis_user');
$QsaveData->bindCrypt(':user_password:', $this->getDoSendElementStorage($ddm_group, 'user_password'));
$QsaveData->bindInt(':user_id:', osW_VIS_User::getInstance()->getId());
$QsaveData->bindInt(':user_update_time:', $vis_time);
$QsaveData->bindInt(':user_update_user_id:', $vis_user_id);
$QsaveData->execute();
if ($QsaveData->query_handler===false) {
	print_a($QsaveData);
}

osW_MessageStack::getInstance()->addSession('session', 'success', array('msg'=>$this->getGroupMessage($ddm_group, 'send_success_title')));

?>