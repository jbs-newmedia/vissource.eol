<?php

$download=h()->_catch('download', 0, 'gp');
$notice_id=intval(h()->_catch('notice_id', 0, 'gp'));

if ((isset($ticket_data['ticket_data'.$download]))&&($ticket_data['ticket_data'.$download]!='')&&($notice_id==0)) {
	header('Content-disposition: attachment; filename='.$ticket_data['ticket_data'.$download.'_name']);
	header('Content-type: '.$ticket_data['ticket_data'.$download.'_mime']);
	header('Content-Length: '.$ticket_data['ticket_data'.$download.'_size']);
	header("Pragma: no-cache");
	header("Expires: 0");
	echo file_get_contents(vOut('settings_abspath').$ticket_data['ticket_data'.$download]);
	h()->_die();
}

if ((isset($notice_data['notice_data'.$download]))&&($notice_data['notice_data'.$download]!='')&&($notice_id)) {
	header('Content-disposition: attachment; filename='.$notice_data['notice_data'.$download.'_name']);
	header('Content-type: '.$notice_data['notice_data'.$download.'_mime']);
	header('Content-Length: '.$notice_data['notice_data'.$download.'_size']);
	header("Pragma: no-cache");
	header("Expires: 0");
	echo file_get_contents(vOut('settings_abspath').$notice_data['notice_data'.$download]);
	h()->_die();
}

if ($ticket_data['ticket_enddate']!=0) {
	$ticket_data['ticket_enddate']=substr($ticket_data['ticket_enddate'], 6, 2).'.'.substr($ticket_data['ticket_enddate'], 4, 2).'.'.substr($ticket_data['ticket_enddate'], 0, 4);
} else {
	$ticket_data['ticket_enddate']='Nicht festgelegt';
}

if (osW_Settings::getInstance()->getAction()=='dosetnotification') {
	if (osW_VIS_Ticket::getInstance()->setTicketNotification($ticket_data['ticket_id'])===true) {
		osW_Settings::getInstance()->setAction('');
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Die Benachrichtigung für dieses Ticket konnte nicht aktiviert werden.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	}
}

if (osW_Settings::getInstance()->getAction()=='dodelnotification') {
	if (osW_VIS_Ticket::getInstance()->delTicketNotification($ticket_data['ticket_id'])===true) {
		osW_Settings::getInstance()->setAction('');
	} else {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Die Benachrichtigung für dieses Ticket konnte nicht deaktiviert werden.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	}

}

$ticket_users=osW_VIS_Ticket::getInstance()->getAllUsers();
$ticket_usersfl=osW_VIS_Ticket::getInstance()->getAllUsers('user_id', 'user_fullnamefl');

$ticket_data['ticket_project']=osW_VIS_Ticket::getInstance()->getProjectData($ticket_data['project_id'], 'project_name');
$ticket_data['ticket_importance']=osW_VIS_Ticket::getInstance()->getImportanceData($ticket_data['importance_id'], 'importance_description');
$ticket_data['ticket_group']=osW_VIS_Ticket::getInstance()->getGroupData($ticket_data['group_id'], 'group_name');
if (($ticket_data['ticket_group']=='')||($ticket_data['ticket_group']==array())) {
	$ticket_data['ticket_group']='---';
}
if (isset($ticket_users[$ticket_data['user_id']])) {
	$ticket_data['ticket_user']=$ticket_users[$ticket_data['user_id']];
} else {
	$ticket_data['ticket_user']='---';
}
$data=osW_VIS_Ticket::getInstance()->getStatus();

if (isset($ticket_users[$ticket_data['ticket_create_user_id']])) {
	$ticket_data['ticket_create_user']=$ticket_usersfl[$ticket_data['ticket_create_user_id']];
} else {
	$ticket_data['ticket_create_user']='';
}
if (isset($ticket_users[$ticket_data['ticket_update_user_id']])) {
	$ticket_data['ticket_update_user']=$ticket_usersfl[$ticket_data['ticket_update_user_id']];
} else {
	$ticket_data['ticket_update_user']='';
}

$ticket_data['ticket_status']=$data[$ticket_data['status_id']];

$ticket_data['ticket_data']=array();
for($i=1;$i<=10;$i++) {
	if ($ticket_data['ticket_data'.$i]!='') {
		$data=array();
		$ticket_data['ticket_data'][]='<a target="blank" href="'.osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$ticket_data['ticket_id'].'&download='.$i, false).'">'.$ticket_data['ticket_data'.$i.'_name'].'</a>';
	}
}

# Notizen laden
$ticket_notizen=osW_VIS_Ticket::getInstance()->getNoticeByTicketId($ticket_data['ticket_id']);
osW_Template::getInstance()->set('ticket_users', $ticket_users);
osW_Template::getInstance()->set('ticket_notizen', $ticket_notizen);


# Formular Notiz

if (!in_array(osW_Settings::getInstance()->getAction(), array('add', 'doadd', 'edit', 'doedit', 'delete', 'dodelete'))) {
	osW_Settings::getInstance()->setAction('add');
}

if (in_array(osW_Settings::getInstance()->getAction(), array('edit', 'doedit', 'delete', 'dodelete'))) {
	$notice_data=osW_VIS_Ticket::getInstance()->getNoticeById(h()->_catch('notice_id'));

	if ($notice_data==array()) {
		osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Die Notiz konnte nicht geöffnet werden.'));
		h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
	}

	if ($notice_data['notice_create_user_id']===osW_VIS_User::getInstance()->getId()) {
		if ((osW_VIS_Permission::getInstance()->checkPermission('view', 'ticket_configure')!==true)) {
			osW_MessageStack::getInstance()->addSession('session', 'error', array('msg'=>'Sie können diese Notiz nicht bearbeiten.'));
			h()->_direct(osW_Template::getInstance()->buildhrefLink(vOut('frame_current_module'), 'vistool='.osW_VIS::getInstance()->getTool()));
		}
	}
}

// DataList

osW_DDM3::getInstance()->addDataElement($ddm_group, 'header', array(
	'module'=>'header',
	'_search'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'search_title', 'messages')
	),
	'_add'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'add_title', 'messages')
	),
	'_edit'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'edit_title', 'messages')
	),
	'_delete'=>array(
		'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'delete_title', 'messages')
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'notice_description', array(
	'module'=>'textarea',
	'title'=>'Notiz',
	'name'=>'notice_description',
	'options'=>array(
		'required'=>true,
		'notice'=>'Mind. 5 Zeichen, max. 10.000 Zeichen'
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>5,
		'length_max'=>10000,
	),
));

for($d=1;$d<=10;$d++) {
	osW_DDM3::getInstance()->addDataElement($ddm_group, 'notice_data'.$d, array(
		'module'=>'file',
		'title'=>'Datei#'.$d,
		'name'=>'notice_data'.$d,
		'options'=>array(
			'file_dir'=>'data/ticket_data',
			'file_name'=>'time+rand',
			'store_name'=>true,
			'store_type'=>true,
			'store_size'=>true,
			'store_md5'=>true,
		),
		'validation'=>array(
			'size_max'=>1024*1024*8,
		),
		'_list'=>array(
			'enabled'=>false,
		),
	));
}

osW_DDM3::getInstance()->addDataElement($ddm_group, 'createupdatestatus', array(
	'module'=>'createupdatestatus',
	'title'=>osW_DDM3::getInstance()->getGroupOption($ddm_group, 'createupdate_title', 'messages'),
	'options'=>array(
		'prefix'=>'notice_',
		'time'=>time(),
		'user_id'=>osW_VIS_User::getInstance()->getId(),
	),
	'_list'=>array(
		'options'=>array(
			'display_create_time'=>false,
			'display_create_user'=>false,
			'display_update_user'=>false,
		),
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_id', array(
	'module'=>'hidden',
	'name'=>'ticket_id',
	'options'=>array(
		'default_value'=>$ticket_data['ticket_id'],
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>11,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
	'module'=>'submit',
));


// FormFinish

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
	'module'=>'store_form_data',
));

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_notice_write', array(
	'module'=>'vis_ticket_notice_write',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

if (in_array(osW_Settings::getInstance()->getAction(), array('add', 'doadd','edit', 'doedit','delete', 'dodelete'))) {
	$css=array();
	$ajax=array();

	for($d=2;$d<=10;$d++) {
		$css[]='.ddm_element_notice_data'.$d.' {display:none;}';
	}

	$ajax[]='

	$(window).load(function() {
		ddm3_formular_'.$ddm_group.'();
		for (var i=1; i<10; i++){
			$("input[name=\'notice_data"+i+"\']").change(function(){ddm3_formular_'.$ddm_group.'("notice_data");});
		}
	});

	function ddm3_formular_'.$ddm_group.'(id) {
		if (!id) {
			id="";
		}

		if ((id=="")||(id=="notice_data")) {
			var data_index=0;
			for (var i=1; i<10; i++){
				var name="notice_data"+i;
				if(($("input[name=\'"+name+"\']").val()!="")||($("input[name=\'"+name+"___osw_delete"+"\']").val())||($("input[name=\'"+name+"___osw_tmp___osw_delete"+"\']").val())) {
					data_index=i;
				}
			}

			for (var i=1; i<=data_index; i++){
				$(".ddm_element_notice_data"+i).fadeIn(0);
			}
			$(".ddm_element_notice_data"+i).fadeIn(0);
		}
	}

	';

	osW_Template::getInstance()->addJSCodeHead(implode("\n", $ajax));
	osW_Template::getInstance()->addCSSCodeHead(implode("\n", $css));
}

?>