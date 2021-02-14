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
  
<?php echo osW_Form::getInstance()->drawRadioField($element, '1', $this->getFormDataElement($ddm_group, $element), array())?><?php echo h()->_outputString($options['message_yes'])?>
&nbsp;
<?php echo osW_Form::getInstance()->drawRadioField($element, '0', $this->getFormDataElement($ddm_group, $element), array())?><?php echo h()->_outputString($options['message_no'])?>

</td>
</tr>