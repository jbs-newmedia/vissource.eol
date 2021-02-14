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

<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> <?php if(osW_Form::getInstance()->getFormError($element)===true):?>table_ddm_row_error<?php endif?> ddm_element_<?php echo $this->getDeleteElementValue($ddm_group, $element, 'id')?> ">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_title"><?php echo h()->outputString($this->getDeleteElementValue($ddm_group, $element, 'title'))?><?php if($this->getDeleteElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_form">
		<?php $ar_tool_user=$this->getDeleteElementStorage($ddm_group, $element); ?>

<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsStructure(0, 'project_id', 'project_name'))>0):?>
		<ul class="table_ddm_list table_ddm_list_vertical table_ddm_list_level_1">
<?php foreach (osW_VIS_Ticket::getInstance()->getProjectsStructure(0, 'project_id', 'project_name') as $tool_id => $tool_name):?>
		<li class="table_ddm_list_subgroup">
			<ul class="table_ddm_list table_ddm_list_vertical table_ddm_list_level_2">
				<li class="table_ddm_list_element">
					<?php if(isset($ar_tool_user[$tool_id])&&($ar_tool_user[$tool_id]==1)):?>
					<?php echo '✔ '.h()->_outputString($tool_name)?>
					<?php else:?>
					<?php echo '✘ '.h()->_outputString($tool_name)?>
					<?php endif?>
				</li>
					<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsStructure($tool_id, 'project_id', 'project_name'))>0):?>
				<?php foreach (osW_VIS_Ticket::getInstance()->getProjectsStructure($tool_id, 'project_id', 'project_name') as $tool_id => $tool_name):?>
				<li class="table_ddm_list_subgroup">
					<ul class="table_ddm_list table_ddm_list_vertical table_ddm_list_level_3">
						<li class="table_ddm_list_element">
							<?php if(isset($ar_tool_user[$tool_id])&&($ar_tool_user[$tool_id]==1)):?>
							<?php echo '✔ '.h()->_outputString($tool_name)?>
							<?php else:?>
							<?php echo '✘ '.h()->_outputString($tool_name)?>
							<?php endif?>
						</li>
					</ul>
				</li>
				<?php endforeach?>
				<?php endif?>
			</ul>
		</li>
<?php endforeach?>
		</ul>
<?php endif?>

	</td>
</tr>