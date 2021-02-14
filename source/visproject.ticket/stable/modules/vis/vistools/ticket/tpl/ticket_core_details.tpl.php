<table class="table_ddm table_ddm_form table_ddm_send">
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_header ddm_element_header">
		<td class="table_ddm_col table_ddm_col_data" colspan="2">Ticket <?php echo h()->_outputString($ticket_data['ticket_number'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Benachrichtigen:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label">
		<?php if (osW_VIS_Ticket::getInstance()->getTicketNotification($ticket_data['ticket_id'])==true):?>
		<a href="<?php echo $this->buildhrefLink('current', 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage().'&ticket_id='.$ticket_data['ticket_id'].'&view=ticket&action=dodelnotification')?>">Deaktivieren</a>
		<?php else:?>
		<a href="<?php echo $this->buildhrefLink('current', 'vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage().'&ticket_id='.$ticket_data['ticket_id'].'&view=ticket&action=dosetnotification')?>">Aktivieren</a>
		<?php endif?>
		</td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Projekt:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_project'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Priorität:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_importance'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Betreff:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_title'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Beschreibung:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_description'])?></td>
	</tr>
	<?php if(!in_array(osW_Settings::getInstance()->getAction(), array('edit', 'delete'))):?>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Gruppe:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_group'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Benutzer:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_user'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Fällig bis:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_enddate'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Status:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo h()->_outputString($ticket_data['ticket_status'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Zeit geplant:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data['ticket_time_planned'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Zeit benötigt:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo osW_VIS_Ticket::getInstance()->outputMinutes($ticket_data['ticket_time_needed'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Dateien:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php if(count($ticket_data['ticket_data'])>0):?><?php echo implode(', ', $ticket_data['ticket_data'])?><?php else:?>---<?php endif?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Erstellt:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo date('d.m.Y, H:i', $ticket_data['ticket_create_time'])?> Uhr von <?php echo h()->_outputString($ticket_data['ticket_create_user'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Bearbeitet:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><?php echo date('d.m.Y, H:i', $ticket_data['ticket_update_time'])?> Uhr von <?php echo h()->_outputString($ticket_data['ticket_update_user'])?></td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title">Optionen:</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label"><a href="<?php echo osW_Template::getInstance()->buildhrefLink('current', 'action=edit&vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage().'&ticket_id='.$ticket_data['ticket_id'])?>">Bearbeiten</a></td>
	</tr>
	<?php endif?>
</table>
<br/>
<?php if(!in_array(osW_Settings::getInstance()->getAction(), array('edit', 'delete'))):?>
<?php if((isset($ticket_notizen['data']))&&($ticket_notizen['data']!=array())):?>
<table class="table_ddm table_ddm_form table_ddm_send">
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_header ddm_element_header">
		<td class="table_ddm_col table_ddm_col_data" colspan="2">Notizen</td>
	</tr>
<?php foreach($ticket_notizen['data'] as $notiz):?>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title" style="line-height:16px; vertical-align:top;"><?php echo h()->_outputString($ticket_users[$notiz['notice_create_user_id']])?></td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label" style="line-height:16px; vertical-align:top;">
			<?php echo h()->_outputString($notiz['notice_description'])?>
			<?php if($notiz['notice_data']!=array()):?>
				<br/><br/>
				Dateien: <?php echo implode(', ', $notiz['notice_data'])?>
			<?php endif?>
		</td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?>">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_title" style="line-height:16px; vertical-align:top; height:16px;"><?php echo date('d.m.Y, H:i', $notiz['notice_create_time'])?> Uhr</td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_label" style="line-height:16px; vertical-align:top; height:16px;">
			<a href="<?php echo $this->buildhrefLink('current', osW_DDM3::getInstance()->getDirectParameters($ddm_group).'&action=edit&notice_id='.$notiz['notice_id'])?>">Bearbeiten</a> | <a href="<?php echo $this->buildhrefLink('current', osW_DDM3::getInstance()->getDirectParameters($ddm_group).'&action=delete&notice_id='.$notiz['notice_id'])?>">Löschen</a>
<?php if(($notiz['notice_create_user_id']!=$notiz['notice_update_user_id'])||($notiz['notice_create_time']!=$notiz['notice_update_time'])):?>
			<span class="right">Zuletzt bearbeitet von <?php echo h()->_outputString($ticket_users[$notiz['notice_update_user_id']])?> am <?php echo date('d.m.Y, H:i', $notiz['notice_update_time'])?> Uhr</span>
<?php endif?>
		</td>
	</tr>
<?php endforeach?>
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_pages">
		<td class="table_ddm_col" colspan="2"><?php echo osW_BuildPages::getInstance()->byLimitRows('current', osW_DDM3::getInstance()->getDirectParameters($ddm_group), $ticket_notizen['limitrows'], array('ddm_group'=>$ddm_group), 'ddm3')?></td>
</tr>
</table>
<br/>
<?php endif?>
<?php endif?>