<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($mail['info'])?></td>
	</tr>
</table>
<br/>
<table width="100%" border="0" bgcolor="#eeeeee" cellpadding="5" cellspacing="2">
	<?php if((isset($mail['notice']))&&($mail['notice']!=array())):?>
	<?php foreach($mail['notice'] as $key => $element):?>
	<?php if($element['changed']===false):?>
	<tr>
		<td width="1%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['info'])?><span style="">&nbsp;&nbsp;&nbsp;</span></td>
		<?php if($key=='notice_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo $element['current']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['current'])?></td>
		<?php endif?>
	</tr>
	<?php else:?>
	<tr>
		<td width="1%" valign="top" bgcolor="#ffffff" rowspan="2" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['info'])?>&nbsp;&nbsp;&nbsp;</td>
		<?php if($key=='notice_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;"><?php echo $element['current']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:green;"><?php echo h()->_outputString($element['current'])?></td>
		<?php endif?>
	</tr>
	<tr>
		<?php if($key=='notice_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;"><?php echo $element['old']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:red;"><?php echo h()->_outputString($element['old'])?></td>
		<?php endif?>
	</tr>
	<?php endif?>
	<?php endforeach?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;" colspan="2">&nbsp;</td>
	</tr>
	<?php endif?>

	<?php foreach($mail['ticket'] as $key => $element):?>
	<?php if($element['changed']===false):?>
	<tr>
		<td width="1%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['info'])?><span style="">&nbsp;&nbsp;&nbsp;</span></td>
		<?php if($key=='ticket_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo $element['current']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['current'])?></td>
		<?php endif?>
	</tr>
	<?php else:?>
	<tr>
		<td width="1%" valign="top" bgcolor="#ffffff" rowspan="2" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['info'])?>&nbsp;&nbsp;&nbsp;</td>
		<?php if($key=='ticket_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;"><?php echo $element['current']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:green;"><?php echo h()->_outputString($element['current'])?></td>
		<?php endif?>
	</tr>
	<tr>
		<?php if($key=='ticket_data'):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;"><?php echo $element['old']?></td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:red;"><?php echo h()->_outputString($element['old'])?></td>
		<?php endif?>
	</tr>
	<?php endif?>
	<?php endforeach?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;">Optionen</td>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px;"><a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$mail['ticket_id'], false)?>">Öffnen</a> | <a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'action=edit&vistool=ticket&vispage=ticket_all&ticket_id='.$mail['ticket_id'], false)?>">Bearbeiten</a></td>
	</tr>
</table>