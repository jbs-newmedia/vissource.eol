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

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>">

	<?php /* label */ ?>
	<label for="<?php echo $element?>"><?php echo h()->outputString($this->getEditElementValue($ddm_group, $element, 'title'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>

	<?php if($this->getEditElementOption($ddm_group, $element, 'read_only')===true):?>

		<?php /* read only */ ?>
		<?php $ar_user_group=$this->getEditElementStorage($ddm_group, $element); ?>
		<?php $ac=count($tools=osW_VIS2_Manager::getInstance()->getTools())?>
		<?php $j;foreach($tools as $tool_id => $tool_name):$j++;?>
			<br/><strong><?php echo h()->_outputString($tool_name)?></strong>
			<?php $c=count($groups=osW_VIS2_Manager::getInstance()->getGroups($tool_id))?>
			<?php if($c>0):?>
				<?php $i=0;foreach($groups as $group_id => $group_name_intern):$i++?>
				<div class="custom-checkbox">
					<?php if(isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id]))&&($ar_user_group[$tool_id][$group_id]==1)):?>
					<?php echo '<i class="fa fa-check" aria-hidden="true"></i> '.h()->_outputString($group_name_intern)?>
					<?php else:?>
					<?php echo '<i class="fa fa-times" aria-hidden="true"></i> '.h()->_outputString($group_name_intern)?>
					<?php endif?>
					<?php echo osW_Form::getInstance()->drawHiddenField($element.'_'.$tool_id.'_'.$group_id, $ar_user_group[$tool_id][$group_id], array())?>
				</div>
				<?php endforeach?>
			<?php else:?>
				<br/>
			<?php endif?>
		<?php endforeach?>

	<?php else:?>

		<?php /* input */ ?>
		<?php $ar_user_group=$this->getEditElementStorage($ddm_group, $element); ?>
		<?php $ac=count($tools=osW_VIS2_Manager::getInstance()->getTools())?>
		<?php $j;foreach($tools as $tool_id => $tool_name):$j++;?>
			<br/><strong><?php echo h()->_outputString($tool_name)?></strong>
			<?php $c=count($groups=osW_VIS2_Manager::getInstance()->getGroups($tool_id))?>
			<?php if($c>0):?>
				<?php $i=0;foreach($groups as $group_id => $group_name_intern):$i++?>
				<div class="custom-control custom-checkbox">
					<?php echo osW_Form::getInstance()->drawCheckBoxField($element.'_'.$tool_id.'_'.$group_id, '1', ((isset($ar_user_group[$tool_id])&&(isset($ar_user_group[$tool_id][$group_id]))&&($ar_user_group[$tool_id][$group_id]==1)) ? 1 : 0), array('input_parameter'=>'title="'.h()->_outputString($group_name_intern).'"', 'input_class'=>'custom-control-input'))?>
					<label class="custom-control-label<?php if(osW_Form::getInstance()->getFormError($element)):?> text-danger<?php endif?>" for="<?php echo $element.'_'.$tool_id.'_'.$group_id ?>0"><?php echo h()->_outputString($group_name_intern)?></label>
				</div>
				<?php endforeach?>
			<?php else:?>
				<br/>
			<?php endif?>
		<?php endforeach?>

	<?php endif?>

	<?php /* error */ ?>
	<?php if(osW_Form::getInstance()->getFormError($element)):?>
		<div class="text-danger small"><?php echo osW_Form::getInstance()->getFormErrorMessage($element)?></div>
	<?php endif?>

	<?php /* notice */ ?>
	<?php if($this->getEditElementOption($ddm_group, $element, 'notice')!=''):?>
		<div class="text-info"><?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'notice'))?></div>
	<?php endif?>

	<?php /* buttons */ ?>
	<?php if($this->getEditElementOption($ddm_group, $element, 'buttons')!=''):?>
		<div>
		<?php echo implode(' ', $this->getEditElementOption($ddm_group, $element, 'buttons'))?>
		</div>
	<?php endif?>

</div>