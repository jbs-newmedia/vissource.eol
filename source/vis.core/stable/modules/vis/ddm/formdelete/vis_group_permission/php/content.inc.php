<?php

$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_group: AS g LEFT JOIN :table_vis_tool: AS t ON (g.tool_id=t.tool_id) WHERE g.group_id=:group_id:');
$QloadData->bindTable(':table_vis_group:', 'vis_group');
$QloadData->bindTable(':table_vis_tool:', 'vis_tool');
$QloadData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
$QloadData->execute();

$ar_tool=array();
if ($QloadData->numberOfRows()==1) {
	$QloadData->next();
	$ar_tool=$QloadData->result;
}
$this->setFormDataElement($ddm_group, 'ar_tool', $ar_tool);

$ar_permission=array();

if (osW_Settings::getInstance()->getAction()=='delete') {
	$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_group_permission: WHERE group_id=:group_id:');
	$QloadData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
	$QloadData->bindInt(':group_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
	$QloadData->execute();
	if ($QloadData->numberOfRows()>0) {
		while ($QloadData->next()>0) {
			$ar_permission[$QloadData->Value('permission_page')][$QloadData->Value('permission_flag')]=1;
		}
	}
}

$this->setFormDataElement($ddm_group, $element, $ar_permission);

if (!isset($this->ddm[$ddm_group]['counts']['form_elements'])) {
	$this->ddm[$ddm_group]['counts']['form_elements']=0;
}
$this->ddm[$ddm_group]['counts']['form_elements']++;


if (!isset($this->ddm[$ddm_group]['counts']['form_required_elements'])) {
	$this->ddm[$ddm_group]['counts']['form_required_elements']=0;
}

if ((isset($options['required']))&&($options['required']===true)) {
	$this->ddm[$ddm_group]['counts']['form_required_elements']++;
}

?>