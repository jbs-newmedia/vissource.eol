<?php

if (osW_Settings::getInstance()->getAction()=='getGroups') {
	$project_id=h()->_catch('project_id', 0, 'p');
	$group_id=h()->_catch('group_id', 0, 'p');

	if ($project_id==0) {
		h()->_die('Bitte Projekt wählen');
	}

	if (($data=osW_VIS_Ticket::getInstance()->getProjectGroups($project_id))!=array()) {
		h()->_die(osW_Form::getInstance()->drawSelectField('group_id', array(''=>'')+$data, $group_id, array()));
	}
	h()->_die('Keine Gruppen zugewiesen');
}

if (osW_Settings::getInstance()->getAction()=='getUsers') {
	$group_id=h()->_catch('group_id', 0, 'p');

	if ($group_id==0) {
		h()->_die('Bitte Gruppe wählen');
	}

	if (($data=osW_VIS_Ticket::getInstance()->getGroupUsers($group_id))!=array()) {
		h()->_die(osW_Form::getInstance()->drawSelectField('user_id', array(''=>'')+$data, '', array()));
	}
	h()->_die('Keine Benutzer zugewiesen');
}

if (osW_Settings::getInstance()->getAction()=='getStatus') {
	$group_id=h()->_catch('group_id', 0, 'p');
	$user_id=h()->_catch('user_id', 0, 'p');

	if (($group_id==0)&&($user_id==0)) {
		h()->_die(osW_Form::getInstance()->drawSelectField('status_id', array(''=>'')+osW_VIS_Ticket::getInstance()->getStatusCore('11001'), '', array()));
	} else {
		h()->_die(osW_Form::getInstance()->drawSelectField('status_id', array(''=>'')+osW_VIS_Ticket::getInstance()->getStatusCore('01111'), '', array()));
	}

	if (($data=osW_VIS_Ticket::getInstance()->getGroupUsers($group_id))!=array()) {
		h()->_die(osW_Form::getInstance()->drawSelectField('user_id', array(''=>'')+$data, '', array()));
	}
	h()->_die('Keine Benutzer zugewiesen');
}

// DataView

osW_DDM3::getInstance()->addPreViewElement($ddm_group, 'navigation', array(
	'module'=>'navigation',
	'options'=>array(
		'data'=>$navigation_links,
	),
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'add_and_search', array(
	'module'=>'add_and_search',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'sortorder', array(
	'module'=>'sortorder',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_header', array(
	'module'=>'table_header',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'table_data', array(
	'module'=>'table_data',
));

osW_DDM3::getInstance()->addViewElement($ddm_group, 'pages', array(
	'module'=>'pages',
));


// DataList

osW_DDM3::getInstance()->addDataElement($ddm_group, 'navigation', array(
	'module'=>'navigation',
	'options'=>array(
		'data'=>$navigation_links,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_id', array(
	'module'=>'index',
	'title'=>'ID',
	'name'=>'ticket_id',
));

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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_number', array(
	'module'=>'autovalue',
	'title'=>'Ticket-Nummer',
	'name'=>'ticket_number',
	'options'=>array(
		'order'=>true,
		'label'=>'wird automatisch vergeben',
		'default_value'=>(date('Ymd')*10000)+1,
		'filter_use'=>false,
	),
	'validation'=>array(
		'filter'=>array(
			'unique_filter'=>array('enabled'=>false),
		),
	),
));

if (!isset($ar_projects)) {
	$ar_projects=osW_VIS_Ticket::getInstance()->getProjectsByUserId();
}

osW_DDM3::getInstance()->addDataElement($ddm_group, 'project_id', array(
	'module'=>'select',
	'title'=>'Projekt',
	'name'=>'project_id',
	'options'=>array(
		'required'=>true,
		'data'=>$ar_projects,
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>11,
	),
	'_search'=>array(
		'options'=>array(
			'blank_value'=>false,
		),
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'importance_id', array(
	'module'=>'select',
	'title'=>'Priorität',
	'name'=>'importance_id',
	'options'=>array(
		'required'=>true,
		'order'=>true,
		'data'=>osW_VIS_Ticket::getInstance()->getImportance(),
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>11,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_title', array(
	'module'=>'text',
	'title'=>'Betreff',
	'name'=>'ticket_title',
	'options'=>array(
		'order'=>true,
		'required'=>true,
	),
	'validation'=>array(
		'length_min'=>3,
		'length_max'=>64,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_description', array(
	'module'=>'textarea',
	'title'=>'Beschreibung',
	'name'=>'ticket_description',
	'options'=>array(
		'required'=>true,
		'notice'=>'Mind. 10 Zeichen, max. 10.000 Zeichen'
	),
	'validation'=>array(
		'module'=>'string',
		'length_min'=>10,
		'length_max'=>10000,
	),
	'_list'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'group_id', array(
	'module'=>'select',
	'title'=>'Gruppe',
	'name'=>'group_id',
	'options'=>array(
		'data'=>osW_VIS_Ticket::getInstance()->getGroups(),
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>0,
		'length_max'=>11,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'user_id', array(
	'module'=>'select',
	'title'=>'Benutzer',
	'name'=>'user_id',
	'options'=>array(
		'data'=>osW_VIS_Ticket::getInstance()->getUsers(),
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>0,
		'length_max'=>11,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_enddate', array(
	'module'=>'datepicker',
	'title'=>'Fällig bis',
	'name'=>'ticket_enddate',
	'options'=>array(
		'order'=>true,
		'month_asname'=>true,
		'year_min'=>2013,
		'year_max'=>date('Y')+1,
		'year_sortorder'=>'asc',
	),
	'_search'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'status_id', array(
	'module'=>'select',
	'title'=>'Status',
	'name'=>'status_id',
	'options'=>array(
		'required'=>true,
		'order'=>true,
		'data'=>osW_VIS_Ticket::getInstance()->getStatusCore(),
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>1,
		'length_max'=>11,
	),
	'_list'=>array(
		'options'=>array(
			'data'=>osW_VIS_Ticket::getInstance()->getStatus(false, false, false),
		),
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_notice_count', array(
	'module'=>'text',
	'title'=>'Kommentare',
	'name'=>'ticket_notice_count',
	'_add'=>array(
		'enabled'=>false,
	),
	'_edit'=>array(
		'enabled'=>false,
	),
	'_delete'=>array(
		'enabled'=>false,
	),
	'_search'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_time_planned', array(
	'module'=>'text',
	'title'=>'Zeit geplant',
	'name'=>'ticket_time_planned',
	'options'=>array(
		'order'=>true,
		'notice'=>'wird in Minuten angegeben',
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>0,
		'length_max'=>11,
	),
	'_list'=>array(
		'enabled'=>false,
	),
	'_search'=>array(
		'enabled'=>false,
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_time_needed', array(
	'module'=>'text',
	'title'=>'Zeit benötigt',
	'name'=>'ticket_time_needed',
	'options'=>array(
		'order'=>true,
		'notice'=>'wird in Minuten angegeben',
	),
	'validation'=>array(
		'module'=>'integer',
		'length_min'=>0,
		'length_max'=>11,
	),
	'_list'=>array(
		'enabled'=>false,
	),
	'_search'=>array(
		'enabled'=>false,
	),
));

for($d=1;$d<=10;$d++) {
	osW_DDM3::getInstance()->addDataElement($ddm_group, 'ticket_data'.$d, array(
		'module'=>'file',
		'title'=>'Datei #'.$d,
		'name'=>'ticket_data'.$d,
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
		'prefix'=>'ticket_',
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

osW_DDM3::getInstance()->addDataElement($ddm_group, 'options', array(
	'module'=>'options',
	'title'=>'Optionen',
	'options'=>array(
		'links_before'=>array(
			array(
				'parameter'=>'view=ticket&vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(),
				'text'=>'Öffnen',
			),
		),
	),
));

osW_DDM3::getInstance()->addDataElement($ddm_group, 'submit', array(
	'module'=>'submit',
));


// FormFinish

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'store_form_data', array(
	'module'=>'store_form_data',
));

osW_DDM3::getInstance()->addFinishElement($ddm_group, 'vis_ticket_write', array(
	'module'=>'vis_ticket_write',
));

osW_DDM3::getInstance()->addAfterFinishElement($ddm_group, 'direct', array(
	'module'=>'direct',
));

if (in_array(osW_Settings::getInstance()->getAction(), array('add', 'doadd','edit', 'doedit','delete', 'dodelete'))) {
	$css=array();
	$ajax=array();

	for($d=2;$d<=10;$d++) {
		$css[]='.ddm_element_ticket_data'.$d.' {display:none;}';
	}

	$ajax[]='
	var global_project_id='.intval(h()->_catch('project_id', '0', 'p')).';
	var global_group_id='.intval(h()->_catch('group_id', '0', 'p')).';
	var global_user_id='.intval(h()->_catch('user_id', '0', 'p')).';
	var global_status_id='.intval(h()->_catch('status_id', '0', 'p')).';

	$(window).load(function() {
		ddm3_formular_'.$ddm_group.'();
		$("select[name=\'project_id\']").change(function(){ddm3_formular_'.$ddm_group.'("project_id");});
		$("select[name=\'group_id\']").change(function(){ddm3_formular_'.$ddm_group.'("group_id");});
		$("select[name=\'user_id\']").change(function(){ddm3_formular_'.$ddm_group.'("user_id");});
		for (var i=1; i<10; i++){
			$("input[name=\'ticket_data"+i+"\']").change(function(){ddm3_formular_'.$ddm_group.'("ticket_data");});
		}
	});

	function ddm3_formular_'.$ddm_group.'(id) {
		if (!id) {
			id="";
		}

		var set_project_id=$("select[name=\'project_id\']").val();
		var set_group_id=$("select[name=\'group_id\']").val();
		var set_user_id=$("select[name=\'user_id\']").val();
		var set_status_id=$("select[name=\'status_id\']").val();

		if ((id=="")||(id=="project_id")) {
			group_id=set_group_id;
			if (global_group_id>0) {
				group_id=global_group_id;
			}

			if($("select[name=\'project_id\']").val()==0) {
				$(".ddm_element_group_id .table_ddm_col_form").html("Bitte Projekt wählen");
			} else {
				value=$("select[name=\'project_id\']").val();
				$.ajax({
					url: "'.osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), osW_DDM3::getInstance()->getDirectParameters($ddm_group)).'",
					type: "POST",
					cache: false,
					data: {
						action: "getGroups",
						project_id: value,
						group_id: group_id
					}
				})
				.done(function(html) {
					$(".ddm_element_group_id .table_ddm_col_form").html(html);
					$("select[name=\'group_id\']").change(function(){ddm3_formular_'.$ddm_group.'("group_id");});
				});
			}
			$(".ddm_element_user_id .table_ddm_col_form").html("Bitte Gruppe wählen");
			if (id=="project_id") {
				$("select[name=\'group_id\']").val("");
				$("select[name=\'user_id\']").val("");
			}

			$.ajax({
				url: "'.osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), osW_DDM3::getInstance()->getDirectParameters($ddm_group)).'",
				type: "POST",
				cache: false,
				data: {
					action: "getStatus",
					group_id: $("select[name=\'group_id\']").val(),
					user_id: $("select[name=\'user_id\']").val(),
					status_id: $("select[name=\'status_id\']").val()
				}
			})
			.done(function(html) {
				$(".ddm_element_status_id .table_ddm_col_form").html(html);
			});
		}

		if ((id=="")||(id=="group_id")) {
			user_id=set_user_id;
			if (global_user_id>0) {
				user_id=global_user_id;
			}

			if($("select[name=\'group_id\']").val()==0) {
				$(".ddm_element_user_id .table_ddm_col_form").html("Bitte Gruppe wählen");
			} else {
				value=$("select[name=\'group_id\']").val();
				$.ajax({
					url: "'.osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), osW_DDM3::getInstance()->getDirectParameters($ddm_group)).'",
					type: "POST",
					cache: false,
					data: {
						action: "getUsers",
						group_id: value,
						user_id: user_id
					}
				})
				.done(function(html) {
					$(".ddm_element_user_id .table_ddm_col_form").html(html);
					$("select[name=\'user_id\']").change(function(){ddm3_formular_'.$ddm_group.'("user_id");});
				});
			}

			$.ajax({
				url: "'.osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), osW_DDM3::getInstance()->getDirectParameters($ddm_group)).'",
				type: "POST",
				cache: false,
				data: {
					action: "getStatus",
					group_id: $("select[name=\'group_id\']").val(),
					user_id: $("select[name=\'user_id\']").val(),
					status_id: $("select[name=\'status_id\']").val()
				}
			})
			.done(function(html) {
				$(".ddm_element_status_id .table_ddm_col_form").html(html);
			});
		}

		if ((id=="")||(id=="user_id")) {

		}

		if ((id=="")||(id=="ticket_data")) {
			var data_index=0;
			for (var i=1; i<10; i++){
				var name="ticket_data"+i;
				if(($("input[name=\'"+name+"\']").val()!="")||($("input[name=\'"+name+"___osw_delete"+"\']").val())||($("input[name=\'"+name+"___osw_tmp___osw_delete"+"\']").val())) {
					data_index=i;
				}
			}

			for (var i=1; i<=data_index; i++){
				$(".ddm_element_ticket_data"+i).fadeIn(0);
			}
			$(".ddm_element_ticket_data"+i).fadeIn(0);
		}
	}

	';

	osW_Template::getInstance()->addJSCodeHead(implode("\n", $ajax));
	osW_Template::getInstance()->addCSSCodeHead(implode("\n", $css));
}

?>