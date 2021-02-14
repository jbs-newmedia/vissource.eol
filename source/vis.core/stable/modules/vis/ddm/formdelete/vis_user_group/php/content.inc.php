<?php

$QloadData=osW_Database::getInstance()->query('SELECT * FROM :table_vis_user_group: WHERE user_id=:user_id: AND tool_id=:tool_id:');
$QloadData->bindTable(':table_vis_user_group:', 'vis_user_group');
$QloadData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
$QloadData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
$QloadData->execute();

$ar_groups=array();
if ($QloadData->numberOfRows()>0) {
	while ($QloadData->next()>0) {
		$ar_groups[$QloadData->Value('group_id')]=1;
	}
}
$this->setFormDataElement($ddm_group, $element, $ar_groups);

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