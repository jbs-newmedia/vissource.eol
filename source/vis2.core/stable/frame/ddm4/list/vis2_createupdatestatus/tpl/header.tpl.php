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

<?php if($this->getListElementOption($ddm_group, $element, 'display_create_time')==true):?>
<th class="ddm_element_<?php echo $this->getListElementValue($ddm_group, $element, 'id')?>_create_time">
	<?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_create_time'))?>
</th>
<?php endif?>

<?php if($this->getListElementOption($ddm_group, $element, 'display_create_user')==true):?>
<th class="ddm_element_<?php echo $this->getListElementValue($ddm_group, $element, 'id')?>_create_user">
	<?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_create_user'))?>
</th>
<?php endif?>

<?php if($this->getListElementOption($ddm_group, $element, 'display_update_time')==true):?>
<th class="ddm_element_<?php echo $this->getListElementValue($ddm_group, $element, 'id')?>_update_time">
	<?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_update_time'))?>
</th>
<?php endif?>

<?php if($this->getListElementOption($ddm_group, $element, 'display_update_user')==true):?>
<th class="ddm_element_<?php echo $this->getListElementValue($ddm_group, $element, 'id')?>_update_user">
	<?php echo h()->outputString($this->getEditElementOption($ddm_group, $element, 'text_update_user'))?>
</th>
<?php endif?>