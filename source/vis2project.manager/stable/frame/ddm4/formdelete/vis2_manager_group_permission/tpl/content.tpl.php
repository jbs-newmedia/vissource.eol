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

if (!function_exists('vis2_manager_group_permission')) {
	function vis2_manager_group_permission($ddm_group, $navigation_element, $ar_permission, $ro=false) {
		?>
				<div style="padding-left:<?php echo (($navigation_element['info']['navigation_level'])*20)?>px">
				<strong><?php echo h()->_outputString($navigation_element['info']['navigation_title'])?></strong>
				<?php if(count($navigation_element['info']['permission'])>0):?>
				<?php foreach($navigation_element['info']['permission'] as $flag):?>
				<?php if($ro===true):?>
				<div class="custom-checkbox">
					<?php if(isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag])):?>
					<?php echo '<i class="fa fa-check" aria-hidden="true"></i> '.h()->_outputString(osW_VIS2_Permission::getInstance()->getPermissionText($flag, $_POST['tool_id']))?>
					<?php else:?>
					<?php echo '<i class="fa fa-times" aria-hidden="true"></i> '.h()->_outputString(osW_VIS2_Permission::getInstance()->getPermissionText($flag, $_POST['tool_id']))?>
					<?php endif?>
					<?php echo osW_Form::getInstance()->drawHiddenField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, $ar_permission[$navigation_element['info']['page_name_intern']][$flag], array())?>
				</div>
				<?php else:?>
				<div class="custom-control custom-checkbox">
					<?php echo osW_Form::getInstance()->drawCheckBoxField('page_'.$navigation_element['info']['page_name_intern'].'_'.$flag, '1', (isset($ar_permission[$navigation_element['info']['page_name_intern']][$flag]) ? $ar_permission[$navigation_element['info']['page_name_intern']][$flag] : 0), array('input_class'=>'custom-control-input'))?>
					<label class="custom-control-label<?php if(osW_Form::getInstance()->getFormError($element)):?> text-danger<?php endif?>" for="<?php echo 'page_'.$navigation_element['info']['page_name_intern'].'_'.$flag ?>0"><?php echo h()->_outputString(osW_VIS2_Permission::getInstance()->getPermissionText($flag, $_POST['tool_id']))?></label>
				</div>
				<?php endif?>
				<?php endforeach?>
				<?php endif?>
				<?php if(count($navigation_element['links'])>0):?>
				<?php foreach($navigation_element['links'] as $_navigation_element):?>
				<?php vis2_manager_group_permission($ddm_group, $_navigation_element, $ar_permission, $ro)?>
				<?php endforeach?>
				<?php endif?>
				</div>
<?php
	}
}

?>

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($ddm_group, $element, 'id')?>">

	<?php /* label */ ?>
	<label for="<?php echo $element?>"><?php echo h()->outputString($this->getDeleteElementValue($ddm_group, $element, 'title'))?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>

	<?php /* read only */ ?>
	<?php $ar_permission=osW_DDM4::getInstance()->getDeleteElementStorage($ddm_group, $element)?>
	<?php foreach (osW_VIS2_Navigation::getInstance()->getNavigationReal(0, osW_DDM4::getInstance()->getGroupOption($ddm_group, 'navigation_level'), $_POST['tool_id']) as $navigation_element):?>
	<?php echo vis2_manager_group_permission($ddm_group, $navigation_element, $ar_permission, true)?>
	<?php endforeach?>

	<?php /* error */ ?>
	<?php if(osW_Form::getInstance()->getFormError($element)):?>
		<div class="text-danger small"><?php echo osW_Form::getInstance()->getFormErrorMessage($element)?></div>
	<?php endif?>

	<?php /* notice */ ?>
	<?php if($this->getDeleteElementOption($ddm_group, $element, 'notice')!=''):?>
		<div class="text-info"><?php echo h()->outputString($this->getDeleteElementOption($ddm_group, $element, 'notice'))?></div>
	<?php endif?>

	<?php /* buttons */ ?>
	<?php if($this->getDeleteElementOption($ddm_group, $element, 'buttons')!=''):?>
		<div>
		<?php echo implode(' ', $this->getDeleteElementOption($ddm_group, $element, 'buttons'))?>
		</div>
	<?php endif?>

</div>