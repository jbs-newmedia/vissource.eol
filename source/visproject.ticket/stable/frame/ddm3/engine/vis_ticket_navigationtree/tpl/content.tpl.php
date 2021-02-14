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

global $navigation_links;

$links=$navigation_links;
$links_anz=0;
if(is_array($links)) {
	$links_anz=count($links);
}

?>


<?php //ADD ?>

<?php if(osW_Settings::getInstance()->getAction()=='add'):?>

<?php echo osW_Form::getInstance()->formStart($ddm_group, 'current', osW_DDM3::getInstance()->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'))?>

<table class="table_ddm table_ddm_form table_ddm_add">

<?php foreach (osW_DDM3::getInstance()->getAddElements($ddm_group) as $element => $options):?>
	<?php echo osW_DDM3::getInstance()->parseFormAddElementTPL($ddm_group, $element, $options)?>
<?php endforeach?>

</table>

<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'doadd')?>
<?php echo osW_Form::getInstance()->drawHiddenField (osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database'), osW_DDM3::getInstance()->getIndexElementStorage($ddm_group))?>
<?php echo osW_Form::getInstance()->formEnd()?>

<?php endif?>



<?php //EDIT?>

<?php if(osW_Settings::getInstance()->getAction()=='edit'):?>

<?php echo osW_Form::getInstance()->formStart($ddm_group, 'current', osW_DDM3::getInstance()->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'))?>

<table class="table_ddm table_ddm_form table_ddm_edit">

<?php foreach (osW_DDM3::getInstance()->getEditElements($ddm_group) as $element => $options):?>
	<?php echo osW_DDM3::getInstance()->parseFormEditElementTPL($ddm_group, $element, $options)?>
<?php endforeach?>

</table>

<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'doedit')?>
<?php echo osW_Form::getInstance()->drawHiddenField (osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database'), osW_DDM3::getInstance()->getIndexElementStorage($ddm_group))?>
<?php echo osW_Form::getInstance()->formEnd()?>

<?php endif?>



<?php //DELETE?>

<?php if(osW_Settings::getInstance()->getAction()=='delete'):?>

<?php echo osW_Form::getInstance()->formStart($ddm_group, 'current', osW_DDM3::getInstance()->getDirectParameters($ddm_group), array('form_parameter'=>'enctype="multipart/form-data"'))?>

<table class="table_ddm table_ddm_form table_ddm_delete">

<?php foreach (osW_DDM3::getInstance()->getDeleteElements($ddm_group) as $element => $options):?>
	<?php echo osW_DDM3::getInstance()->parseFormDeleteElementTPL($ddm_group, $element, $options)?>
<?php endforeach?>

</table>

<?php echo osW_Form::getInstance()->drawHiddenField ('action', 'dodelete')?>
<?php echo osW_Form::getInstance()->drawHiddenField (osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database'), osW_DDM3::getInstance()->getIndexElementStorage($ddm_group))?>
<?php echo osW_Form::getInstance()->formEnd()?>

<?php endif?>



<?php //DATA ?>

<?php if(osW_Settings::getInstance()->getAction()==''):?>

<?php

function vis_ticket_recursive($ddm_group, $project_element) {
	$output='';

	return $output;
}

?>
<table class="table_ddm table_ddm_navigation">
<?php if($links_anz>0):?>
<tr class="table_ddm_row table_ddm_row_navigation ddm_element_<?php echo $this->getViewElementValue($ddm_group, $element, 'id')?>">
	<td class="table_ddm_col" colspan="3">
		<ul class="table_ddm_list table_ddm_list_horizontal">
<?php $i=0;foreach($links as $link_id => $__link):$i++;?>
<?php
if (isset($__link['navigation_id'])) {
	$link_id=$__link['navigation_id'];
}
?>
			<li>
				<a<?php if($this->getParameter($ddm_group, 'ddm_navigation_id')==$link_id):?> class="active"<?php endif?><?php echo (((isset($__link['target']))) ? ' target="'.$__link['target'].'"' : '');?> href="<?php echo osW_Template::getInstance()->buildhrefLink(((($__link['module'])) ? $__link['module'] : $this->getDirectModule($ddm_group)), 'ddm_navigation_id='.$link_id.((($__link['parameter'])) ? '&'.$__link['parameter'] : ''))?>"><?php echo ((($__link['text'])) ? h()->_outputString($__link['text']) : 'undefined')?></a>
			</li>
<?php endforeach?>
		</ul>
	</td>
</tr>
<?php endif?>
	<tr class="table_ddm_row table_ddm_row_line">
		<td class="table_ddm_col" colspan="3">
			<span class="left"><a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=add&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php echo $this->getGroupMessage($ddm_group, 'add_title')?></a></span>
		</td>
	</tr>
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_header">
		<td class="table_ddm_col table_ddm_col_header">Beschreibung</td>
		<td class="table_ddm_col table_ddm_col_header">Status</td>
		<td class="table_ddm_col table_ddm_col_header">Sortierung</td>
		<td class="table_ddm_col table_ddm_col_header">Optionen</td>
	</tr>
<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsStructure())>0):?>
<?php foreach (osW_VIS_Ticket::getInstance()->getProjectsStructure() as $project_element):?>


<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> table_ddm_row_data_level_0">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_title"><?php echo h()->_outputString($project_element['project_name'])?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><?php echo $project_element['project_status']?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><?php echo $project_element['project_sortorder']?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options">
		<a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=add&'.osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index_parent').'='.$project_element['project_id'].'&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_add')?></a> |
		<a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=edit&'.osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database').'='.$project_element['project_id'].'&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php  echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_edit')?></a> |
<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsStructure($project_element['project_id']))==0):?>
		<a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=delete&'.osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database').'='.$project_element['project_id'].'&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_delete')?></a>
<?php else:?>
		 <?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_delete')?>
<?php endif?>
	</td>
</tr>



<?php if(count(osW_VIS_Ticket::getInstance()->getProjectsStructure($project_element['project_id']))>0):?>
<?php foreach (osW_VIS_Ticket::getInstance()->getProjectsStructure($project_element['project_id']) as $project_sub_element):?>


<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', array('table_ddm_row_cella', 'table_ddm_row_cellb'))?> table_ddm_row_data_level_1">
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_title"><?php echo h()->_outputString($project_sub_element['project_name'])?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><?php echo $project_sub_element['project_status']?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options"><?php echo $project_sub_element['project_sortorder']?></td>
	<td class="table_ddm_col table_ddm_col_data table_ddm_col_data_options">
		<?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_add')?></a> |
		<a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=edit&'.osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database').'='.$project_sub_element['project_id'].'&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_edit')?></a> |
		<a href="<?php echo osW_Template::getInstance()->buildhrefLink(osW_DDM3::getInstance()->getDirectModule($ddm_group), 'action=delete&'.osW_DDM3::getInstance()->getGroupOption($ddm_group, 'index', 'database').'='.$project_sub_element['project_id'].'&'.osW_DDM3::getInstance()->getDirectParameters($ddm_group))?>"><?php echo osW_DDM3::getInstance()->getGroupMessage($ddm_group, 'data_delete')?></a>
	</td>
</tr>
<?php endforeach?>
<?php endif?>




<?php endforeach?>
<?php else:?>
<tr class="table_ddm_row table_ddm_row_data">
	<td class="table_ddm_col" colspan="4">
<?php echo $this->getGroupMessage($ddm_group, 'data_noresults')?>
	</td>
</tr>
<?php endif?>
	<tr class="table_ddm_row table_ddm_row_highlight table_ddm_row_footer">
		<td class="table_ddm_col table_ddm_col_footer" colspan="4">&nbsp;</td>
	</tr>
</table>

<?php endif?>