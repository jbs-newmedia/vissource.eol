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

if (!function_exists('vis_manager_group_permission')) {
	function vis_manager_group_permission($ddm_group, $navigation_element, $ar_permission) {

?>
		<li class="table_ddm_list_subgroup">
			<ul class="table_ddm_list table_ddm_list_vertical table_ddm_list_level_<?php echo ($navigation_element['info']['navigation_level']+2)?>">
				<li class="table_ddm_list_title"><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?></li>
				<?php if(count($navigation_element['info']['permission'])>0):?>
				<?php foreach($navigation_element['info']['permission'] as $flag):?>
				<li class="table_ddm_list_element">
   					<?php if(isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])&&($ar_permission[$navigation_element['info']['page_name_intern']][$flag]==1)):?>
					<?php echo '✔ '.h()->_outputString(osW_VIS_Permission::getInstance()->getPermissionText($flag, osW_VIS::getInstance()->getToolId()))?>
					<?php else:?>
					<?php echo '✘ '.h()->_outputString(osW_VIS_Permission::getInstance()->getPermissionText($flag, osW_VIS::getInstance()->getToolId()))?>
					<?php endif?>
				<?php endforeach?>
				<?php endif?>
				<?php if(count($navigation_element['links'])>0):?>
				<?php foreach($navigation_element['links'] as $_navigation_element):?>
				<?php vis_manager_group_permission($ddm_group, $_navigation_element, $ar_permission)?>
				<?php endforeach?>
				<?php endif?>
			</ul>
		</li>
<?php
	}
}

?>

<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> <?php if(osW_Form::getInstance()->getFormError($element)===true):?>table_ddm_row_error<?php endif?> ddm_element_<?php echo $this->getDeleteElementValue($ddm_group, $element, 'id')?> ">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_title"><?php echo h()->outputString($this->getDeleteElementValue($ddm_group, $element, 'title'))?><?php if($this->getDeleteElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_form">
	<?php $ar_permission=osW_DDM3::getInstance()->getDeleteElementStorage($ddm_group, $element)?>
		<ul class="table_ddm_list table_ddm_list_vertical table_ddm_list_level_1">
<?php foreach (osW_VIS_Navigation::getInstance()->getNavigationReal(0, osW_DDM3::getInstance()->getGroupOption($ddm_group, 'navigation_level'), osW_VIS::getInstance()->getToolId()) as $navigation_element):?>
  <?php echo vis_manager_group_permission($ddm_group, $navigation_element, $ar_permission)?>
<?php endforeach?>
		</ul>
	</td>
</tr>