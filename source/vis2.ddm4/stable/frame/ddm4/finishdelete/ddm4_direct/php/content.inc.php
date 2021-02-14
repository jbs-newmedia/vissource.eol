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

$_messages=array();
if(osW_MessageStack::getInstance()->size('ddm4_'.$ddm_group)) {
	foreach (osW_MessageStack::getInstance()->getClass('ddm4_'.$ddm_group) as $type => $messages) {
		foreach ($messages as $message) {
			$_messages[]='window.parent.vis2_notify("'.$message['msg'].'", "'.$type.'");';
		}
	}
	osW_MessageStack::getInstance()->reset('ddm4_'.$ddm_group);
}

osW_Template::getInstance()->addJSCodeHead('
$(function() {
	window.parent.ddm4datatables.ajax.reload(null, false);
	window.parent.$(".modal").modal("hide");
'.implode("\n", $_messages).'
});
');

?>