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

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($ddm_group, $element, 'id')?>">

	<?php /* label */ ?>
	<label for="<?php echo $element?>"><?php echo h()->outputString($this->getDeleteElementValue($ddm_group, $element, 'title'))?><?php if($this->getDeleteElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>

	<?php /* read only */ ?>
	<?php $ar_navigation_permission=$this->getDeleteElementStorage($ddm_group, $element)?>
	<?php $permission_list=osW_VIS2_Permission::getInstance()->getPermissionTextList($_POST['tool_id'])?>
	<?php $c=count(osW_VIS2_Permission::getInstance()->getPermissionTextList($_POST['tool_id']))?>
	<?php if(!empty($permission_list)):?>
		<?php $i=0;foreach(osW_VIS2_Permission::getInstance()->getPermissionTextList($_POST['tool_id']) as $permission_flag => $permission_text):$i++;?>
		<div class="custom-checkbox">
			<?php if(isset($ar_navigation_permission[$permission_flag])&&($ar_navigation_permission[$permission_flag]==1)):?>
			<?php echo '<i class="fa fa-check" aria-hidden="true"></i> '.h()->_outputString($permission_text)?>
			<?php else:?>
			<?php echo '<i class="fa fa-times" aria-hidden="true"></i> '.h()->_outputString($permission_text)?>
			<?php endif?>
			<?php echo osW_Form::getInstance()->drawHiddenField($element.'_'.$permission_flag, $ar_navigation_permission[$permission_flag], array())?>
		</div>
		<?php endforeach?>
	<?php endif?>

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