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

# search
if(osW_Settings::getInstance()->getAction()=='search') {
	osW_Template::getInstance()->addCSSCodeHead('
html,
body {
	height: 100%;
}

html,
body,
.page-wrapper-color {
	min-height: 100%;
}
');

	osW_Template::getInstance()->addJSCodeHead('
function submitDDM4(del) {
	if (del===true) {
		$("input[name=ddm4_search_delete]").val(1);
	}
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-color">';
	echo osW_Form::getInstance()->formStart($ddm_group, 'current', $this->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'));
	foreach ($this->getSearchElements($ddm_group) as $element => $options) {
		echo $this->parseFormSearchElementTPL($ddm_group, $element, $options);
	}
	echo osW_Form::getInstance()->drawHiddenField('action', 'dosearch');
	echo osW_Form::getInstance()->drawHiddenField('ddm4_search_delete', 0);
	echo osW_Form::getInstance()->drawHiddenField($this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
	echo osW_Form::getInstance()->formEnd();
	echo '</div>';
}

# add
if(osW_Settings::getInstance()->getAction()=='add') {
	osW_Template::getInstance()->addCSSCodeHead('
html,
body {
	height: 100%;
}

html,
body,
.page-wrapper-color {
	min-height: 100%;
}
');

	osW_Template::getInstance()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-color">';
	echo osW_Form::getInstance()->formStart($ddm_group, 'current', $this->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'));
	foreach ($this->getAddElements($ddm_group) as $element => $options) {
		echo $this->parseFormAddElementTPL($ddm_group, $element, $options);
	}
	echo osW_Form::getInstance()->drawHiddenField('action', 'doadd');
	echo osW_Form::getInstance()->drawHiddenField($this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
	echo osW_Form::getInstance()->formEnd();
	echo '</div>';
}

# edit
if(osW_Settings::getInstance()->getAction()=='edit') {
	osW_Template::getInstance()->addCSSCodeHead('
html,
body {
	height: 100%;
}

html,
body,
.page-wrapper-color {
	min-height: 100%;
}
');

	osW_Template::getInstance()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}

run=true;

function checkDMM4() {
	if (run===true) {
		url="'.osW_Template::getInstance()->buildhrefLink('current', $this->getDirectParameters($ddm_group)).'";
		$.post(url, {
			action: "dolock",
			'.$this->getGroupOption($ddm_group, 'index', 'database').': "'.$this->getIndexElementStorage($ddm_group).'"
		}, function(data){});
		setTimeout(checkDMM4, 5000);
	}
}

$(function() {
	checkDMM4();
});

');
	echo '<div class="page-wrapper-color">';
	if ($this->setLock($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database')))!==true) {
		echo '<div class="alert alert-danger" role="alert" style="margin:15px 0px;">'.h()->_setText($this->getGroupMessage($ddm_group, 'lock_error'), array('user'=>$this->getLockUser($ddm_group, h()->_catch($this->getGroupOption($ddm_group, 'index', 'database'))))).'</div>';
	}
	echo osW_Form::getInstance()->formStart($ddm_group, 'current', $this->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'));
	foreach ($this->getEditElements($ddm_group) as $element => $options) {
		echo $this->parseFormEditElementTPL($ddm_group, $element, $options);
	}
	echo osW_Form::getInstance()->drawHiddenField('action', 'doedit');
	echo osW_Form::getInstance()->drawHiddenField($this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
	echo osW_Form::getInstance()->formEnd();
	echo '</div>';
}

# delete
if(osW_Settings::getInstance()->getAction()=='delete') {
	osW_Template::getInstance()->addCSSCodeHead('
html,
body {
	height: 100%;
}

html,
body,
.page-wrapper-color {
	min-height: 100%;
}
');

	osW_Template::getInstance()->addJSCodeHead('
function submitDDM4() {
	$("form").submit();
}
function resetDDM4() {
	$("form").trigger("reset");
	$(".selectpicker").selectpicker("render");
}
');
	echo '<div class="page-wrapper-color">';
	echo osW_Form::getInstance()->formStart($ddm_group, 'current', $this->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'));
	foreach ($this->getDeleteElements($ddm_group) as $element => $options) {
		echo $this->parseFormDeleteElementTPL($ddm_group, $element, $options);
	}
	echo osW_Form::getInstance()->drawHiddenField ('action', 'dodelete');
	echo osW_Form::getInstance()->drawHiddenField ($this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
	echo osW_Form::getInstance()->formEnd();
	echo '</div>';
}

# data
if(in_array(osW_Settings::getInstance()->getAction(), array('', 'log'))) {
	osW_Template::getInstance()->addCSSCodeHead('
html,
body {
	height: 100%;
}

html,
body,
.page-wrapper-color {
	min-height: 100%;
}
');

	if (osW_Settings::getInstance()->getAction()!='') {
		$ddm_group.='_'.osW_Settings::getInstance()->getAction();
		echo '<div class="page-wrapper-color">';
	} else {
		echo '<div class="card shadow mb-4"><div class="card-body">';
	}

	if($this->getPreViewElements($ddm_group)!=array()) {
		foreach ($this->getPreViewElements($ddm_group) as $element => $options) {
			$file=vOut('settings_abspath').'frame/ddm4/view/'.$options['module'].'/tpl/content.tpl.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}

	if($this->getViewElements($ddm_group)!=array()) {
		foreach ($this->getViewElements($ddm_group) as $element => $options) {
			$file=vOut('settings_abspath').'frame/ddm4/view/'.$options['module'].'/tpl/content.tpl.php';
			if (file_exists($file)) {
				include $file;
			}
		}
	}

	if (osW_Settings::getInstance()->getAction()!='') {
		echo '</div>';
	} else {
		echo '</div></div>';
	}

	?>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="ddm4modal_dialog_<?php echo $ddm_group?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h5 class="modal-title"></h5></div>
			<div class="modal-body"><p></p></div>
			<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->getGroupMessage($ddm_group, 'form_close')?></button></div>
		</div>
	</div>
</div>


<div class="modal fade" data-backdrop="static" data-keyboard="false" id="ddm4_controller_<?php echo $ddm_group?>" style="width:100%;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="justify-content: none; ">
				<h5 class="modal-title float-left" style="float:left !important;"></h5>
				<span class="float-right">
				<button type="button" class="float-right close" data-dismiss="modal" aria-label="<?php echo $this->getGroupMessage($ddm_group, 'form_close')?>"><i class="fa fa-window-close" aria-hidden="true"></i></button>
				<button type="button" class="float-right close resize" onclick="resizeDDM4Modal()" aria-label="<?php echo $this->getGroupMessage($ddm_group, 'form_maximize')?>"><i class="fa fa-window-maximize" aria-hidden="true"></i></button>
				</span>
			</div>
			<div class="modal-body" style="padding:0px; margin:0px; overflow:hidden;"></div>
			<div class="modal-footer">
				<button onclick="submitDDM4Modal_<?php echo $ddm_group?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_search"><?php echo $this->getGroupMessage($ddm_group, 'form_search')?></button>
				<button onclick="submitDDM4Modal_<?php echo $ddm_group?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_add"><?php echo $this->getGroupMessage($ddm_group, 'form_add')?></button>
				<button onclick="submitDDM4Modal_<?php echo $ddm_group?>();" name="ddm4_button_submit" type="button" class="btn btn-primary ddm4_btn_edit"><?php echo $this->getGroupMessage($ddm_group, 'form_edit')?></button>
				<button onclick="submitDDM4Modal_<?php echo $ddm_group?>(true);" name="ddm4_button_delete" type="button" class="btn btn-danger ddm4_btn_delete"><?php echo $this->getGroupMessage($ddm_group, 'form_delete')?></button>
				<button onclick="resetDDM4Modal_<?php echo $ddm_group?>();" name="ddm4_button_reset" type="button" class="btn btn-default ddm4_btn_reset"><?php echo $this->getGroupMessage($ddm_group, 'form_reset')?></button>
				<button name="ddm4_button_close" type="button" class="btn btn-default ddm4_btn_close" data-dismiss="modal"><?php echo $this->getGroupMessage($ddm_group, 'form_close')?></button>
				<button name="ddm4_button_cancel" type="button" class="btn btn-default ddm4_btn_cancel" data-dismiss="modal"><?php echo $this->getGroupMessage($ddm_group, 'form_cancel')?></button>
			</div>
		</div>
	</div>
</div>

<?php
osW_Template::getInstance()->addJSCodeHead('


$( window ).resize(function() {
	sizeDDM4Modal();
});

function sizeDDM4Modal() {
	if ($("#ddm4_controller_'.$ddm_group.'").hasClass("modal-fullscreen")) {
		$("#ddm4_controller_'.$ddm_group.' .modal-dialog .ddm4_iframe_holder").css("height", ($( window ).height()-122)+"px");
		$("#ddm4_controller_'.$ddm_group.' .modal-dialog .ddm4_iframe_content").css("height", ($( window ).height()-122)+"px");
	} else {
		$("#ddm4_controller_'.$ddm_group.' .modal-dialog .ddm4_iframe_holder").css("height", "600px");
		$("#ddm4_controller_'.$ddm_group.' .modal-dialog .ddm4_iframe_content").css("height", "600px");
	}
}

function resizeDDM4Modal() {
	$("#ddm4_controller_'.$ddm_group.'").toggleClass("modal-fullscreen", 1000, "easeOutSine");
	$("#ddm4_controller_'.$ddm_group.' .modal-header .resize .fa").toggleClass("fa-window-maximize");
	$("#ddm4_controller_'.$ddm_group.' .modal-header .resize .fa").toggleClass("fa-window-minimize");
	sizeDDM4Modal();
}

function openDDM4Notify_'.$ddm_group.'(elem) {
	$.getScript($(elem).attr("pageName"), function() {});
	ddm4datatables.ajax.reload(null, false);
}

function openDDM4Modal_'.$ddm_group.'(elem, title, mode, count) {
	if (!mode) {
		mode="";
	}
	if (!count) {
		count=0;
	}

	$("#ddm4_controller_'.$ddm_group.'").removeClass("modal-log");
	if (mode=="add") {
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="edit") {
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="delete") {
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="search") {
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="search_edit") {
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").show();
	} else if (mode=="log") {
		$("#ddm4_controller_'.$ddm_group.'").addClass("modal-log");
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").hide();
	} else if (mode=="modal") {
		$("#ddm4_controller_'.$ddm_group.'").addClass("modal-log");
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_search").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_add").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_edit").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_delete").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_reset").hide();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_close").show();
		$("#ddm4_controller_'.$ddm_group.' .modal-footer .ddm4_btn_cancel").hide();
	}
	$("#ddm4_controller_'.$ddm_group.' .modal-header h5").html(title);
	$("#ddm4_controller_'.$ddm_group.'.modal .modal-body").html("<div class=\"ddm4_iframe_holder\"><iframe class=\"ddm4_iframe_content\"></iframe></div>");
	$("#ddm4_controller_'.$ddm_group.' .ddm4_iframe_content").attr("src", $(elem).attr("pageName"));
	//$("#ddm4_controller_'.$ddm_group.'.modal .modal-content").load();
	$("#ddm4_controller_'.$ddm_group.'.modal").modal("show");
	sizeDDM4Modal();

	$("#ddm4_controller_'.$ddm_group.'").on("hidden.bs.modal", function () {
		$(".ddm4_iframe_content")[0].contentWindow.run=false;
	});
}

function submitDDM4Modal_'.$ddm_group.'(del) {
	$(".ddm4_iframe_content")[0].contentWindow.submitDDM4(del);
}

function resetDDM4Modal_'.$ddm_group.'() {
	$(".ddm4_iframe_content")[0].contentWindow.resetDDM4();
}
');

}

?>