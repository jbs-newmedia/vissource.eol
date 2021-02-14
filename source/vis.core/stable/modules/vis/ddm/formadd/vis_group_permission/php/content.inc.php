<?php

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