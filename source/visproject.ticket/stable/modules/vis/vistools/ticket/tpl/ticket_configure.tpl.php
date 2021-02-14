<?php if($ddm_navigation_id==1):?>

<?

global $navigation_links;

$links=$navigation_links;
$links_anz=0;
if(is_array($links)) {
	$links_anz=count($links);
}

?>

<?php //DATA ?>

<?php if(osW_Settings::getInstance()->getAction()==''):?>

<?php

function vis_ticket_recursive($ddm_group, $project_element) {
	$output='';

	return $output;
}

?>
<?php echo osW_Form::getInstance()->formStart($ddm_group, vOut('frame_current_module'), 'ddm_navigation_id='.$ddm_navigation_id.'&vistool='.osW_VIS::getInstance()->getTool().'&vispage='.osW_VIS_Navigation::getInstance()->getPage(), array('form_parameter'=>'enctype="multipart/form-data"'))?>
<table class="table_ddm table_ddm_navigation">
<?php if($links_anz>0):?>
<tr class="table_ddm_row table_ddm_row_navigation">
	<td class="table_ddm_col" colspan="3">
		<ul class="table_ddm_list table_ddm_list_horizontal">
<?php $i=0;foreach($links as $link_id => $__link):$i++;?>
<?php
if (isset($__link['navigation_id'])) {
	$link_id=$__link['navigation_id'];
}
?>

			<li>
				<a<?php if($ddm_navigation_id==$link_id):?> class="active"<?php endif?><?php echo (((isset($__link['target']))) ? ' target="'.$__link['target'].'"' : '');?> href="<?php echo osW_Template::getInstance()->buildhrefLink(((($__link['module'])) ? $__link['module'] : vOut('frame_current_module')), 'ddm_navigation_id='.$link_id.((($__link['parameter'])) ? '&'.$__link['parameter'] : ''))?>"><?php echo ((($__link['text'])) ? h()->_outputString($__link['text']) : 'undefined')?></a>
			</li>
<?php endforeach?>
		</ul>
	</td>
</tr>
<?php endif?>
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_header">
		<td class="table_ddm_col table_ddm_col_header">Projekt</td>
		<td class="table_ddm_col table_ddm_col_header">Optionen</td>
	</tr>

<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array'))>0):?>
<?php foreach (osW_VIS_Ticket::getInstance()->getProjectsByUserId(0, 'project_id', 'array') as $project_id => $project_element):?>

<?php if ($project_element['project_parent_id']==0):?>
<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> table_ddm_row_data_level_0">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_title"><?php echo h()->_outputString($project_element['project_name'])?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><span style="vertical-align:middle;"><?php echo osW_Form::getInstance()->drawCheckboxField('project_'.$project_id, 1, $ar_notify_project[$project_id])?></span> Benachrichtigen, <span style="vertical-align:middle;"><?php echo osW_Form::getInstance()->drawCheckboxField('project_sub_'.$project_id, 1, $ar_notify_project_sub[$project_id])?></span> inklusive aller Unterprojekte</td>
</tr>
<?php else:?>
<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> table_ddm_row_data_level_1">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_title"><?php echo h()->_outputString($project_element['project_name'])?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><span style="vertical-align:middle;"><?php echo osW_Form::getInstance()->drawCheckboxField('project_'.$project_id, 1, $ar_notify_project[$project_id])?></span> Benachrichtigen</td>
</tr>
<?php endif?>

<?php endforeach?>

	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_footer">
		<td class="table_ddm_col table_ddm_col_footer" colspan="2"><?php echo osW_Form::getInstance()->drawSubmit('btn_ddm_submit', 'Speichern')?></td>
	</tr>

<?php else:?>
<tr class="table_ddm_row table_ddm_row_data">
	<td class="table_ddm_col" colspan="2">
Keine Projekte vorhanden
	</td>
</tr>
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_footer">
		<td class="table_ddm_col table_ddm_col_footer" colspan="2">&nbsp;</td>
	</tr>
<?php endif?>
<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'dosave')?>
<?php echo osW_Form::getInstance()->formEnd()?>
</table>

<?php endif?>

<?php else:?>

<?php echo osW_DDM3::getInstance()->runDDMTPL($ddm_group)?>

<?php endif?>