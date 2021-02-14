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

<?php if($this->getEditElementValue($ddm_group, $element, 'title')!=''):?>
	<h4 class="form-group ddm4_element_header ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>"><?php echo h()->outputString($this->getEditElementValue($ddm_group, $element, 'title'))?></h4>
<?php endif?>

<?php if($this->getEditElementOption($ddm_group, $element, 'display_create_time')==true):?>
<div class="form-group ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>">
	<label class="form-control-label"><?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_create_time'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>
	<?php if(($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_time')=='')||($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_time')=='0')):?>
		<div class="form-control readonly">---</div>
	<?php else:?>
		<?php if($this->getEditElementOption($ddm_group, $element, 'month_asname')===true):?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getEditElementOption($ddm_group, $element, 'date_format')), $this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_time'))?> <?php echo h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'))?></div>
		<?php else:?>
			<div class="form-control readonly"><?php echo strftime($this->getEditElementOption($ddm_group, $element, 'date_format'), $this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_time'))?> <?php echo h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'))?></div>
		<?php endif?>
	<?php endif?>
</div>
<?php endif?>

<?php if($this->getEditElementOption($ddm_group, $element, 'display_create_user')==true):?>
<div class="form-group ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>">
	<label class="form-control-label"><?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_create_user'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>
	<?php if(($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_user_id')=='')||($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_user_id')=='0')||(osW_VIS2::getInstance()->getUsernameById($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_user_id'))=='')):?>
		<div class="form-control readonly">---</div>
	<?php else:?>
		<div class="form-control readonly"><?php echo h()->_outputString(osW_VIS2::getInstance()->getUsernameById($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'create_user_id')))?></div>
	<?php endif?>
</div>
<?php endif?>

<?php if($this->getEditElementOption($ddm_group, $element, 'display_update_time')==true):?>
<div class="form-group ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>">
	<label class="form-control-label"><?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_update_time'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>
	<?php if(($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_time')=='')||($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_time')=='0')):?>
		<div class="form-control readonly">---</div>
	<?php else:?>
		<?php if($this->getEditElementOption($ddm_group, $element, 'month_asname')===true):?>
			<div class="form-control readonly"><?php echo strftime(str_replace('%m.', ' %B ', $this->getEditElementOption($ddm_group, $element, 'date_format')), $this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_time'))?> <?php echo h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'))?></div>
		<?php else:?>
			<div class="form-control readonly"><?php echo strftime($this->getEditElementOption($ddm_group, $element, 'date_format'), $this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_time'))?> <?php echo h()->_outputString($this->getGroupMessage($ddm_group, 'text_clock'))?></div>
		<?php endif?>
	<?php endif?>
</div>
<?php endif?>

<?php if($this->getEditElementOption($ddm_group, $element, 'display_update_user')==true):?>
<div class="form-group ddm_element_<?php echo $this->getEditElementValue($ddm_group, $element, 'id')?>">
	<label class="form-control-label"><?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_update_user'))?><?php if($this->getEditElementOption($ddm_group, $element, 'required')===true):?><?php echo $this->getGroupMessage($ddm_group, 'form_title_required_icon')?><?php endif?><?php echo $this->getGroupMessage($ddm_group, 'form_title_closer')?></label>
	<?php if(($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_user_id')=='')||($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_user_id')=='0')||(osW_VIS2::getInstance()->getUsernameById($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_user_id'))=='')):?>
		<div class="form-control readonly">---</div>
	<?php else:?>
		<div class="form-control readonly"><?php echo h()->_outputString(osW_VIS2::getInstance()->getUsernameById($this->getEditElementStorage($ddm_group, $this->getEditElementOption($ddm_group, $element, 'prefix').'update_user_id')))?></div>
	<?php endif?>
</div>
<?php endif?>