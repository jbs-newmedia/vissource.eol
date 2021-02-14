<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
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

?>

<tr class="ddm_datarow <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('cella', 'cellb'))?>">
<td class="ddm_datacol ddm_datacol_title"><?php echo $this->getFormEditElementOption($ddm_group, $element, 'title')?><?php if($this->getFormEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getMessage($ddm_group, 'form_title_closer')?></td>
<td class="ddm_datacol ddm_datacol_form">

<?php
$ar_tool=$this->getFormDataElement($ddm_group, 'ar_tool');
$navigation=osW_VIS_Navigation::getInstance()->getNavigation($ar_tool['tool_id']);
$i=0;
foreach ($navigation as $navigation_group_id => $navigation_group_details) {
	if ((!isset($navigation_group_details['custom']))||($navigation_group_details['custom']!==true)) {
		$i++;
		if($i>1) {
			echo '<br/>';
		}
		echo '<strong>'.h()->_outputString($navigation_group_details['navigation_title']).'</strong><br/>';
		if (count($navigation_group_details['pages'])>0) {
			foreach ($navigation_group_details['pages'] as $navigation_page_id => $navigation_page_details) {
				if ((!isset($navigation_page_details['custom']))||($navigation_page_details['custom']!==true)) {
					echo '↳ <strong>'.h()->_outputString($navigation_page_details['navigation_title']).'</strong><br/>';
					if (count($navigation_page_details['navigation_permissionflags'])>0) {
						foreach ($navigation_page_details['navigation_permissionflags'] as $flag) {
							echo ' &nbsp; '.osW_Form::getInstance()->drawCheckBoxField($navigation_page_details['navigation_page'].'_'.$flag, '1', 0, array())?> <?php echo h()->_outputString(osW_VIS_Permission::getInstance()->getPermissionText($flag, $ar_tool['tool_id'])).'<br/>';
						}
					}
					if (count($navigation_page_details['pages'])>0) {
						foreach ($navigation_page_details['pages'] as $navigation_subpage_id => $navigation_subpage_details) {
							if ((!isset($navigation_subpage_details['custom']))||($navigation_subpage_details['custom']!==true)) {
								echo ' &nbsp; &nbsp; ↳ <strong>'.h()->_outputString($navigation_subpage_details['navigation_title']).'</strong><br/>';
								if (count($navigation_subpage_details['navigation_permissionflags'])>0) {
									foreach ($navigation_subpage_details['navigation_permissionflags'] as $flag) {
										echo ' &nbsp; &nbsp; &nbsp; '.osW_Form::getInstance()->drawCheckBoxField($navigation_subpage_details['navigation_page'].'_'.$flag, '1', 0, array())?> <?php echo h()->_outputString(osW_VIS_Permission::getInstance()->getPermissionText($flag, $ar_tool['tool_id'])).'<br/>';
									}
								}
								if ((!isset($permission[$navigation_subpage_details['navigation_page']]['link']))||($permission[$navigation_subpage_details['navigation_page']]['link']!==true)) {
									unset($navigation[$navigation_group_id]['pages'][$navigation_page_id]['pages'][$navigation_subpage_id]);
								}
							}
						}
					}
				}
			}
		}
	}
}

?>

</td>
</tr>