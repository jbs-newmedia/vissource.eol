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

?>

<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> <?php if(osW_Form::getInstance()->getFormError($element)===true):?>table_ddm_row_error<?php endif?> ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?> ">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_title"><?php echo h()->outputString($this->getEditElementValue($ddm_group, $element, 'title'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_form">
		<?php $ar_user_group=$this->getEditElementStorage($ddm_group, $element); ?>
		<ul class="table_ddm_list table_ddm_list_vertical">
			<?php foreach(osW_VIS_Manager::getInstance()->getGroups(osW_VIS::getInstance()->getToolID()) as $group_id => $group_name_intern):?>
			<li class="table_ddm_list_element"><?php echo osW_Form::getInstance()->drawCheckBoxField($element.'_'.$group_id, '1', ((isset($ar_user_group[osW_VIS::getInstance()->getToolID()])&&(isset($ar_user_group[osW_VIS::getInstance()->getToolID()][$group_id]))&&($ar_user_group[osW_VIS::getInstance()->getToolID()][$group_id]==1)) ? 1 : 0), array())?> <?php echo h()->_outputString($group_name_intern)?></li>
			<?php endforeach?>
		</ul>
	</td>
</tr>