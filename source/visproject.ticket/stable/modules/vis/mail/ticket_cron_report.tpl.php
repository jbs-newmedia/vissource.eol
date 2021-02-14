<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($mail['info'])?></td>
	</tr>
</table>
<br/>
	<?php if((isset($mail['null']))&&($mail['null']!=array())):?>
<br/><strong>Ohne Ablaufdatum:</strong><br/><br/>
<table width="100%" border="0" bgcolor="#eeeeee" cellpadding="5" cellspacing="2">
	<tr>
		<td width="16%%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Ticket-Nummer</td>
		<td width="55%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold;">Betreff</td>
		<td width="11%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Fällig bis</td>
		<td width="18%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Optionen</td>
	</tr>
	<?php foreach($mail['null'] as $element):?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['ticket_number'])?></td>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['ticket_title'])?></td>
		<?php if($element['ticket_enddate']==0):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap>---</td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo substr($element['ticket_enddate'], 6, 2).'.'.substr($element['ticket_enddate'], 4, 2).'.'.substr($element['ticket_enddate'], 0, 4)?></td>
		<?php endif?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Öffnen</a> | <a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'action=edit&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Bearbeiten</a></td>
	</tr>
	<?php endforeach?>
</table>
<br/>
	<?php endif?>

	<?php if((isset($mail['future']))&&($mail['future']!=array())):?>
<br/><strong>Geplante Tickets:</strong><br/><br/>
<table width="100%" border="0" bgcolor="#eeeeee" cellpadding="5" cellspacing="2">
	<tr>
		<td width="16%%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Ticket-Nummer</td>
		<td width="55%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold;">Betreff</td>
		<td width="11%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Fällig bis</td>
		<td width="18%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Optionen</td>
	</tr>
	<?php foreach($mail['future'] as $element):?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['ticket_number'])?></td>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['ticket_title'])?></td>
		<?php if($element['ticket_enddate']==0):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap>---</td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo substr($element['ticket_enddate'], 6, 2).'.'.substr($element['ticket_enddate'], 4, 2).'.'.substr($element['ticket_enddate'], 0, 4)?></td>
		<?php endif?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Öffnen</a> | <a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'action=edit&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Bearbeiten</a></td>
	</tr>
	<?php endforeach?>
</table>
<br/>
	<?php endif?>

	<?php if((isset($mail['now']))&&($mail['now']!=array())):?>
<br/><strong>Dringende Tickets:</strong><br/><br/>
<table width="100%" border="0" bgcolor="#eeeeee" cellpadding="5" cellspacing="2">
	<tr>
		<td width="16%%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Ticket-Nummer</td>
		<td width="55%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold;">Betreff</td>
		<td width="11%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Fällig bis</td>
		<td width="18%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Optionen</td>
	</tr>
	<?php foreach($mail['now'] as $element):?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['ticket_number'])?></td>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['ticket_title'])?></td>
		<?php if($element['ticket_enddate']==0):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap>---</td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo substr($element['ticket_enddate'], 6, 2).'.'.substr($element['ticket_enddate'], 4, 2).'.'.substr($element['ticket_enddate'], 0, 4)?></td>
		<?php endif?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Öffnen</a> | <a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'action=edit&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Bearbeiten</a></td>
	</tr>
	<?php endforeach?>
</table>
<br/>
	<?php endif?>

	<?php if((isset($mail['next']))&&($mail['next']!=array())):?>
<br/><strong>Kommende Tickets:</strong><br/><br/>
<table width="100%" border="0" bgcolor="#eeeeee" cellpadding="5" cellspacing="2">
	<tr>
		<td width="16%%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Ticket-Nummer</td>
		<td width="55%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold;">Betreff</td>
		<td width="11%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Fällig bis</td>
		<td width="18%" valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; font-weight:bold; nowrap">Optionen</td>
	</tr>
	<?php foreach($mail['next'] as $element):?>
	<tr>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo h()->_outputString($element['ticket_number'])?></td>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($element['ticket_title'])?></td>
		<?php if($element['ticket_enddate']==0):?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap>---</td>
		<?php else:?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;" nowrap><?php echo substr($element['ticket_enddate'], 6, 2).'.'.substr($element['ticket_enddate'], 4, 2).'.'.substr($element['ticket_enddate'], 0, 4)?></td>
		<?php endif?>
		<td valign="top" bgcolor="#ffffff" style="font-family:verdana; font-size:12px; color:#000000;"><a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'view=ticket&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Öffnen</a> | <a style="color:#0000ff; text-decoration:none;" href="<?php echo osW_Template::getInstance()->buildhrefLink('vis', 'action=edit&vistool=ticket&vispage=ticket_all&ticket_id='.$element['ticket_id'], false)?>">Bearbeiten</a></td>
	</tr>
	<?php endforeach?>
</table>
<br/>
	<?php endif?>
